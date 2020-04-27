<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Log;
/**
 * handle product related api calls
 */
class ProductController extends Controller
{
    /**
     * get all the products of nearby seller of a user
     */
    public static function getSellersProducts($sellers)
    {
        try{
            return Product::getSellersItems($sellers);
        }
        catch(QueryException $e)
        {
            return [];
        } 
    }


    /**
     * get all product of a seller
     */
    public static function getSellerItems(Request $request)
    {
        $seller=Auth::user();
        $items=Product::where('seller_id',$seller->id)->get();
        if($items){
            return response(['error'=>false,'items'=>$items],200);
        }
        else{
            return response(['error'=>true,'message'=>'no item found'],200);
        }
    }


    /**
     * save a product of a seller
     */
    public static function store(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
        $seller=Auth::user();
        try{
            $res= Product::store($seller->id,$reqData);
            if($res){
                return response(['error'=>false,'id'=>$res],200);
            }
            else{
                return response(['error'=>true,'message'=>'failed'],200);
            }
        }catch(QueryException $e){
            return response(['error'=>true,'message'=>$e],200);
        }
    }

    
    /**
     * update a product details by seller
     * only authorized seller can update details
     */
    public static function update(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
        $seller=Auth::user();
        $id=$reqData['item_id'];
        unset($reqData['item_id']);
        foreach($reqData as $key=>$value){
            switch($key){
                case 'discount':
                    if($value>100 || $value<0){
                        return response(['error'=>true,'message'=>'invalid discount'],406);
                    }
                break;
                case 'price':
                    if($value<0){
                        return response(['error'=>true,'message'=>'invalid price'],406);
                    }
                break;
                case 'stock':
                    if($value<0){
                        return response(['error'=>true,'message'=>'invalid stock'],406);
                    }
                break;
                default:
                return response(['error'=>true,'message'=>'invalid update'],406);
            }
        }
        try{
        $up=Product::where('id',$id)
                ->where('seller_id',$seller->id)
                ->update($reqData); 
        return response(['error'=>false,'status'=>$up],200);
        }
        catch(QueryException $e){
            return response(['error'=>true,'message'=>$e],200);
        } 
    }


    /**
     * delete a product by authorized seller
     */
    public static function delete(Request $request)
    {
        $idsStr=$request->input('ids');
        $ids=explode(',',$idsStr);
        $seller=Auth::user();
        try{
        $status=Product::where('seller_id',$seller->id)
                ->whereIn('id',$ids)
                ->delete();
        return response(['error'=>false,'status'=>$status],200);
        }
        catch(QueryException $e){
            return response(['error'=>true,'message'=>$e],200);
        }
    }
}
