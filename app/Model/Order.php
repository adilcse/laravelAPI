<?php

namespace App\Model;
use DB;
use Log;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    protected $table='orders';
    /**
     * create a new order by user
     */
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


    /**
     * insert order items in order item table
     */
    public static function storeOrderItem($orderId,$item)
    {
        try{
            DB::table('order_items')
                ->insert([
                    'order_id'=>$orderId,
                    'item_id'=>$item->id,
                    'quantity'=>$item->quantity,
                    'price'=>$item->price
                ]);
        }
        catch(QueryException $e){
            return false;
        }
    }


    /**
     * get all orders of a user or seller
     */
    public static function getOrderWithItems($from,$user_id,$per_page)
    {
        //get order details with items and address
        $orders=Order::select("orders.*",
                            "order_items.order_id",
                            "order_items.item_id",
                            "products.name AS item_name",
                            "products.image AS item_image",
                            "order_items.quantity AS item_quantity",
                            "order_items.price AS item_price",
                            "order_items.confirmed AS item_status",
                            "address.name AS loc_name",
                            "address.number AS loc_number",
                            "address.address AS loc_address",
                            "address.locality AS loc_locality",
                            "address.landmark AS loc_landmark",
                            "address.city AS loc_city",
                            "address.pin AS loc_pin",
                            "address.lat AS loc_lat",
                            "address.lng AS loc_lng",
                            "sellers.name AS seller_name"
                            )
                ->join('order_items','orders.id','=','order_items.order_id')
                ->join('products','order_items.item_id','=','products.id')
                ->join('address','address.id','=','orders.address_id')
                ->join('sellers','sellers.id','=','orders.seller_id')
                ->where("orders.".$from,$user_id) 
                ->latest()
                ->get();
        $order_ids=[];
        $my_orders=[];
        //create item and address as object of an order
        //remove different rows for same order of different item and place items inside an order
        foreach($orders->all() as $order){
            $order=(object)$order->original;
            $item=[ 'name'=>$order->item_name,
                    'id'=>$order->item_id,
                    'image'=>$order->item_image,
                    'quantity'=>$order->item_quantity,
                    'price'=>$order->item_price,
                    'accept'=>$order->item_status];
                    unset($order->item_name);
                    unset($order->item_id);
                    unset($order->item_image);
                    unset($order->item_price);
                    unset($order->item_quantity);
                    unset($order->item_status);
            if(!in_array($order->id,$order_ids)){
                $address=["name"=>$order->loc_name,
                        "number"=>$order->loc_number,
                        "address"=>$order->loc_address,
                        "locality"=>$order->loc_locality,
                        "landmark"=>$order->loc_landmark,
                        "city"=>$order->loc_city,
                        "pin"=>$order->loc_pin,
                        "lat"=>$order->loc_lat,
                        "lng"=>$order->loc_lng];
                unset($order->loc_name);
                unset($order->loc_number);
                unset($order->loc_address);
                unset($order->loc_locality);
                unset($order->loc_landmark);
                unset($order->loc_city);
                unset($order->loc_pin);
                unset($order->loc_lat);
                unset($order->loc_lng);
                array_push($order_ids,$order->id);
                $order->items=[$item];
                $order->delivery_address=$address;
                array_push($my_orders,(array)$order);
            }else{
                $index=array_search($order->id, array_column($my_orders, 'id'));
                    array_push($my_orders[$index]['items'],$item);
            } 
        }
        return $my_orders;
    }


    /**
     * reject order by seller
     * @param order id
     */
    public static function reject($id)
    {
        return Order::where('id',$id)
                ->update(['status'=>'CANCELLED']);
    }


    /**
     * accept order by seller and reduce stock
     */
    public static function accept($id,$refund,$reject)
    {
        $items=DB::table('order_items')
                    ->select('order_items.item_id',
                    'order_items.quantity',
                    'order_items.confirmed',
                    'products.stock'
                    )
                ->where('order_items.order_id',$id)
                ->join('products','products.id','=','order_items.item_id')
                ->get();
        foreach($items as $item){
            if($item->confirmed===1){
                $new_stock=$item->stock - $item->quantity;
                if($new_stock>0)
                    DB::table('products')
                        ->where('id',$item->item_id)
                        ->update(['stock'=>$new_stock]);
                else
                DB::table('order_items')
                    ->where('order_id',$id)
                    ->where('item_id',$item->item_id)
                    ->update(['confirmed'=>0]);
            }
        }
        Order::where('id',$id)
        ->update(['status'=>'ACCEPTED',
                'refund_amount'=>$refund,
                'rejected_items'=>$reject]);
    }


    /**
     * get order item by order id
     * @param order_id
     * @return items of requested order
     */
    public static function getItems($id)
    {
        try{
                return DB::table('order_items')
                    ->select('order_id','item_id','quantity','price','confirmed AS accept')
                    ->where('order_id',$id)
                    ->get();
        }
        catch(QueryException $e){
            return false;
        }      
    }


    /**
     * update item status
     * @param item_id
     * @param order_id
     * @param item_status 0,1
     */
    public static function updateOrderItem($item_id,$order_id,$status)
    {
        try{
            
            return DB::table('order_items')
                    ->where('order_id',$order_id)
                    ->where('item_id',$item_id)
                    ->update(['confirmed'=>$status]);
        }
        catch(QueryException $e){
            return false;
        }         
    }


    /**
     * update order status
     * @param order_id
     * @param suatus
     */
    public static function statusUpdate($id,$status)
    {
        if($status==='DELIVERED'){
            return Order::where('id',$id)
                        ->update(['status'=>$status,'delivered_at'=>DB::raw('now()')]);
        }
        else{
            return Order::where('id',$id)
                        ->update(['status'=>$status]);
        }
    }

}
