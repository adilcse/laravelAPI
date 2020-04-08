<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Address;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers\CartController;
use App\Model\Cart;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
   public static function getUserCart($id)
   {   
        $cartItems= Cart::getCartItems($id);
        foreach($cartItems as $key=>$value){
            $value['MRP']=$value["price"];
            $value['price']=$value['price'] - floor($value['price'] * $value['discount']/100);
            $cartItems[$key]=$value;
        }
        return ['cart'=>$cartItems];    
   }
   public static function addToCart(Request $request)
   {
    $reqData=(array)json_decode($request->input('json'));
    $userObj = Auth::user();
    $id= $userObj->id;
    try{
    $reqData=array_merge($reqData,['user_id'=>$id]);  
    $result= Cart::addItem($reqData);
    return response(['cart_id'=>$result],200);
    }catch(Exception $e){
        return response($e,403);
    }

   }

   public static function removeFromCart($item_id)
   {
    $userObj = Auth::user();
    $id= $userObj->id;
    try{
       $res = Cart::where('user_id','=',$id)
        ->where('item_id','=',$item_id)
        ->delete();
        return response(['status'=>$res],200);
    }
    catch(QueryException $e){
 return response(['status'=>$e],403);
    }
}
  
    public static function updateCart(Request $request)
    {
    
        $reqData=(array)json_decode($request->input('json'));
        $userObj = Auth::user();
        $user_id= $userObj->id;
        try{
            $content= Cart::updateCart($user_id,$reqData['item_id'],$reqData['quantity']);
            return response(['status'=>$content],200);
        }catch(QueryException $e)
        {
            return response(['error'=>$e],403);
        }
       
    }
   
}
