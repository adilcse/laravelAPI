<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Product;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public static function getSellersProducts($sellers)
    {
        try{
            return Product::getSellersItems($sellers);
        }
        catch(QueryException $e)
        {
            return null;
        }
       
    }
    public static function getSellerItems(Request $request)
    {
        $seller=Auth::user();
        $items=Product::where('seller_id',$seller->id)->get();
        if($items){
            return response(['items'=>$items],200);
        }else{
            return response(['error'=>'no item found'],200);
        }
    }
    public static function store(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
        $seller=Auth::user();
        try{
           $res= Product::store($seller->id,$reqData);
           if($res)
                return response(['id'=>$res],200);
            else
                return response(['error'=>'failed'],200);
        }catch(QueryException $e){
            return response(['error'=>$e],200);
        }
    }

}
