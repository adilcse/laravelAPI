<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    protected $table='orders';
    public static function store($order,$address_id,$user)
    {
        try{
            return Order::insertGetId([
                'user_id'=>$user,
                'seller_id'=>$order->seller_id,
                'payment_mode'=>$order->paymentMode,
                'status'=>$order->status,
                'total_amount'=>$order->total->total,
                'total_items'=>$order->total->itemCount,
                'delivery_amount'=>$order->total->deliveryCharges,
                'address_id'=>$address_id
            ]);
    }
    catch(QueryException $e){
        
        return false;
    }
    }
    public static function storeOrderItem($order_id,$item)
    {
       try{
           DB::table('order_items')
        ->insert([
            'order_id'=>$order_id,
            'item_id'=>$item->id,
            'quantity'=>$item->quantity,
            'price'=>$item->price
        ]);
       }
       catch(QueryException $e){
           return false;
       }
    }
}
