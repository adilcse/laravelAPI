<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Order;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
/**
 * Order controller handle all order CURD calls  
 */
class OrderController extends Controller
{
    /**
     * create a new order and store it in database
     * @param request user request with order details
     */
    public function store(Request $request)
    {
        $user=Auth::user();
        $reqData=json_decode($request->input('json'));
        try{
            //save delivery address and get address id
            $addressId=AddressController::saveAddress((array)$reqData->address,'USER');
            if($reqData->address->updateAddress && $user->address_id){
                UserController::updateAddressId($user->id,$addressId);
                AddressController::deleteAddress($user->address_id);   
            }
            else if(!$user->address_id){
                UserController::updateAddressId($user->id,$addressId);
            }
            foreach($reqData->order as $ord){
                //place order and add address id 
                $orderId= Order::store($ord,$addressId,$user->id);
                if($orderId){
                    //store items in order item table of an order
                    foreach($ord->items as $item){
                        Order::storeOrderItem($orderId,$item);
                    }
                }
            }
            $from=$reqData->from;
            //clear cart after order placed
            if('cart' === $from){
                CartController::emptyUserCart($user->id);
            }
        return response(['error'=>false,'status'=>'success'],200);
        }
        catch(QueryException $e){
            return response(['error'=>true,'message'=>$e],403);
        }
    }


    /**
     * get order of a user 
     * @param user_requst 
     * @param order per page
     * @retun order
     */
    public static function getUserOrder(Request $request,$perPage)
    {
        $from='user_id';
        if($request->is('seller/*')){
            $from='seller_id';
        }
        $user=Auth::user();
        try{   
            //get all orders along with item dtails
            $order=Order::getOrderWithItems($from,$user->id,$perPage);
            return response(['error'=>false,'order'=>$order],200);
        }
        catch(QueryException $e){
            return response(['error'=>true,'message'=>$e],403);
        }
    }


    /**
     * seller accept or reject an order 
     * @param Request
     * @param orderId
     */
    public function acceptReject(Request $request)
    {
        try{
            $reqData=json_decode($request->input('json'));
            $id=$reqData->id;
            $user=Auth::user();
            $order=Order::find($id);
            //return if seller is not authorized
            if($user->id != $order->seller_id){
                return response(['error'=>true,'message'=>'seller id not matched'],403);
            }
            $orderItems=Order::getItems($id);
            $items=$reqData->items;
            $status=$reqData->status;
            if(!$status){
                $data = Order::reject($id);
            }
            else{
            // for partial accept calculate refund  
                $refund_amount=0;
                $rejectedItems=0;
                foreach($items as $item){
                    $searchItem = null;
                    foreach($orderItems as $struct) {
                        if ($item->id === $struct->item_id) {
                            $searchItem = $struct;
                            break;
                        }    
                    }
                    if($searchItem){
                        if($item->accept!=$searchItem->accept){
                        Order::updateOrderItem($item->id,$id,$item->accept);
                        }
                        //calculate refund amount
                        if(!$item->accept){
                            $refund_amount+=$searchItem->price*$searchItem->quantity;
                            $rejectedItems++;
                            }
                        }   
                    }
                    //accept order
                $data=Order::accept($id,$refund_amount,$rejectedItems);
                }    
                return response(['error'=>false,'status'=>$data],200);
        }
        catch(Exception $e){
            return response(['error'=>true,'message'=>$e],200);
        }
    }


    /**
     * update an order status by seller
     */
    public function update(Request $request,$id)
    {
        $user=Auth::user();
        $order=Order::find($id);
        //return if seller is not authorized 
        if($user->id != $order->seller_id){
            return response(['error'=>true,'message'=>'seller id not matched'],403);
        }
        $status=$request->input('status');
        $error=false;
        //only allow limited update function
        switch($order->status){
            case 'PENDING':
                if($status==='ACCEPTED' || $status==='CANCELLED'){
                    Order::statusUpdate($id,$status);
                }
                else{
                    $error=true;
                }
            break;
            case 'ACCEPTED':
                if($status==='OUT_FOR_DELIVERY'){
                    Order::statusUpdate($id,$status);
                }
                else{ 
                    $error=true;
                }
            break;
            case 'OUT_FOR_DELIVERY':
                if($status==='DELIVERED'){
                    Order::statusUpdate($id,$status);
                }
                else {
                    $error=true;
                }
            break;
            default:
                $error=true;
        }
        if($error){
            return response(['error'=>true,'message'=>'invalid status'],403);
        }
        else{
            return response(['error'=>false],200);
        }
    }
} 