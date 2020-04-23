<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * Cart model handle cart CURD operation
 */
class Cart extends Model
{
    protected $table='carts';
    /**
     * get cart items by user id
     * @param user_id whose cart to be fetched
     */
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
    /**
     * add item to cart
     * @param item_details
     * @return new_cart_id
     */
    public static function addItem($data)
    {
        return DB::table('carts')
        ->insertGetId($data);
    }
    /**
     * update cart of a user
     * @param user_id
     * @param item_id
     * @param quantity
     */
    public static function updateCart($userId,$itemId,$quantity)
    {
        return Cart::where('user_id','=',$userId)
                ->where('item_id','=',$itemId)
                ->update(['quantity'=>$quantity]);
    }
}
