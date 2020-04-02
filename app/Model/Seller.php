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
        $query='SELECT id,`name`,`address`,`lat`,`lng`, ( 6371 * acos( cos( radians('.$mylat.') ) 
        * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$mylng.') ) 
        + sin( radians('.$mylat.') ) * sin( radians( lat ) ) ) ) 
        AS distance FROM sellers HAVING distance < '.$dist.' 
        ORDER BY distance LIMIT 0 , '.$lmt.';';
        $value=DB::select($query);
        return $value;
      }
}
