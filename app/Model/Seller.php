<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Seller extends Model
{
  /**
   * return all sellers available in the database;
   */
    public static function getAllSeller(){
        $value=DB::table('sellers')->orderBy('name', 'asc')->get();
        return $value;
      }
      /**
       * return sellers within a geographic region;
       * @param {} $mylat
       * @param {} $mylng
       * @param { } $dist
       * 
       */
      public static function within($mylat,$mylng,$dist,$lmt=100){
        // $query='SELECT id,`name`,`address`,`lat`,`lng`, ( 6371 * acos( cos( radians('.$mylat.') ) 
        // * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$mylng.') ) 
        // + sin( radians('.$mylat.') ) * sin( radians( lat ) ) ) ) 
        // AS distance FROM address   HAVING distance < '.$dist.' 
        // ORDER BY distance LIMIT 0 , '.$lmt.';';
        // $value=DB::select($query);
        $value=DB::table('sellers')
                ->join('address','sellers.address_id','=','address.id')
                ->join('indian_states','address.state_id','=','indian_states.id')
                ->select(DB::raw('getDistance(address.lat,address.lng,'.$mylat.','.$mylng.') AS distance'),
                        'sellers.name as shop_name',
                        'sellers.email',
                        'sellers.id',
                        'address.name as seller_name',
                        'address.pin',
                        'address.lat',
                        'address.lng',
                        'address.number',
                        'address.city',
                        'address.state_id',
                        'indian_states.name as state',
                        'address.landmark',
                        'address.locality')
                ->having('distance','<',$dist)
                ->where('sellers.current_status','=','ACTIVE')
                ->limit($lmt)
                ->get();
        return $value;
      }
}
