<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * product model handle product curd operation
 */
class Product extends Model
{
    /**
     * get all items of requested sellers
     * @param sellers_ids array
     * @return items 
     */
    public static function getSellersItems($sellers)
    {
        return Product::whereIn('seller_id',$sellers)->get()->toArray();
    }
    /**
     * store a product to product table by seler
     * @param seller_id
     * @param product_data
     * @return product_Id
     */
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
