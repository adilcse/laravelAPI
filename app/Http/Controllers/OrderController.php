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

}