<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Cart extends Model
{
    protected $table='carts';
    public static function getCartItems($id)
    {
       $itemsObj= DB::table('carts')
                    ->select('carts.id as cart_id',
                            'carts.item_id',
                            'carts.quantity',
                            'carts.created_at as added_at',
                            'products.name',
                            'products.image',
                            'products.price',
                            'products.discount',
                            'products.seller_id',
                            'products.stock')
                    ->join('products','carts.item_id','=','products.id')
                    ->where('carts.user_id',$id)->get();
       $items=[];
       foreach($itemsObj as $item){
           array_push($items,(array)$item);
       }
       return $items;
    }

    public static function addItem($data)
    {
        return DB::table('carts')
        ->insertGetId($data);
    }
    public static function updateCart($user_id,$item_id,$quantity)
    {
      return Cart::where('user_id','=',$user_id)
       ->where('item_id','=',$item_id)
       ->update(['quantity'=>$quantity]);
    }
}
