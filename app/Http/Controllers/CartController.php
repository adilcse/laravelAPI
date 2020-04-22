<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Model\Cart;
use Illuminate\Support\Facades\Auth;
/**
 * handle API call for any cart CURD operation
 */
class CartController extends Controller
{
    /**
     * get cart of any registered user by its id
     * @param {*} id
     */
    public static function getUserCart($id)
    {   
        $cartItems= Cart::getCartItems($id);
        foreach($cartItems as $key=>$value){
            $value['MRP']=$value["price"];
            $value['price']=$value['price'] - floor($value['price'] * $value['discount']/100);
            $cartItems[$key]=$value;
        }
        return $cartItems;    
    }
    /**
     * add item to user's cart and return the cart id
     */
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
    /**
     * remove any item from cart by item id
     * @param item_id for item to remove
     * @return true,false if success or failed to remove
     */
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
    /**
     * update any cart item quantity 
     */
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
    /**
     * clear user's cart by user id
     * @param user_id id of user whose cart is to be cleared
     */
    public static function emptyUserCart($user_id)
    {
        try{
        return Cart::where('user_id',$user_id)->delete();
        }
        catch(QueryException $e){
            return false;
        }
    }  
}
