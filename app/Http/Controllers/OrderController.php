<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
 
class OrderController extends Controller
{

    public function store(Request $request)
    {
        $user=Auth::user();
        $reqData=json_decode($request->input('json'));
        try{
            $address_id=AddressController::saveAddress((array)$reqData->address,'USER');
            if($reqData->address->updateAddress && $user->address_id)
            {
                UserController::updateAddressId($user->id,$address_id);
                AddressController::deleteAddress($user->address_id);   
            }
            else if(!$user->address_id)
                UserController::updateAddressId($user->id,$address_id);
            foreach($reqData->order as $ord){
                $order_id= Order::store($ord,$address_id,$user->id);
                if($order_id){
                    foreach($ord->items as $item){
                        Order::storeOrderItem($order_id,$item);
                    }
                }
            }
            $from=$reqData->from;
            if($from=='cart')
                CartController::emptyUserCart($user->id);
        return response(['status'=>'success'],200);
        }
        catch(QueryException $e){
            return response(['error'=>$e],403);
        }
    }

    public static function getUserOrder(Request $request,$per_page)
    {
        $from='user_id';
        if($request->is('seller/*'))
            $from='seller_id';
        $user=Auth::user();
        try{
            
          $order=Order::getOrderWithItems($from,$user->id,$per_page);
          return response(['order'=>$order],200);
        }
        catch(QueryException $e){
           return response(['error'=>$e],403);
        }
    }
    public function acceptReject(Request $request,$id)
    {
        try{
        $user=Auth::user();
        $order=Order::find($id);
        if($user->id != $order->seller_id)
            return response(['status'=>false,'error'=>'seller id not matched'],403);
        $order_items=Order::getItems($id);
        $reqData=json_decode($request->input('json'));
        $items=$reqData->items;
        $status=$reqData->status;
        if(!$status)
            Order::reject($id);
        else{
         //   
            $refund_amount=0;
            $rejected_items=0;
            foreach($items as $item){
                $search_item = null;
                foreach($order_items as $struct) {
                    if ($item->id == $struct->item_id) {
                        $search_item = $struct;
                        break;
                    }    
                }
                if($search_item){
                    if($item->accept!=$search_item->accept){
                    Order::updateOrderItem($item->id,$id,$item->accept);
                    }
                    if(!$item->accept){
                        $refund_amount+=$search_item->price*$search_item->quantity;
                        $rejected_items++;
                        }
                    }   
                }
            $data=Order::accept($id,$refund_amount,$rejected_items);

            }
                
            return response(['status'=>true,'data'=>$data],200);
        }
        catch(Exception $e){
            return response(['status'=>false,'error'=>$e],200);
        }
    }
    public function update(Request $request,$id)
    {
        $user=Auth::user();
        $order=Order::find($id);
        if($user->id != $order->seller_id)
            return response(['status'=>false,'error'=>'seller id not matched'],403);
        $status=$request->input('status');
        $error=false;
        switch($order->status){
            case 'PENDING':
                if($status=='ACCEPTED' || $status=='CANCELLED')
                    Order::statusUpdate($id,$status);
                else
                    $error=true;
            break;
            case 'ACCEPTED':
                if($status=='OUT_FOR_DELIVERY')
                    Order::statusUpdate($id,$status);
                else 
                    $error=true;
            break;
            case 'OUT_FOR_DELIVERY':
                if($status=='DELIVERED')
                    Order::statusUpdate($id,$status);
                else 
                    $error=true;
            break;
            default:
                $error=true;
        }
        if($error)
            return response(['status'=>false,'error'=>'invalid status'],403);
        else
        return response(['status'=>true],200);
    }

} 