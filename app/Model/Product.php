<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
    public static function getSellersItems($sellers)
    {
        Product::whereIn('seller_id',$sellers)->get()->toArray();
    }
    public static function store($id,$data)
    {
     return  Product::insertGetId([
           'seller_id'=>$id,
           'name'=>$data['name'],
           'description'=>$data['description'],
           'image'=>$data['image'],
           'price'=>$data['price'],
           'discount'=>$data['discount'],
           'stock'=>$data['stock'],
           'catagory_id'=>$data['catagory']
       ]);
    }
}
