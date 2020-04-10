<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Address;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers\UserController;

class AddressController extends Controller
{
    public static function getUserAddress($id)
    {
        try{
            return (array)Address::getAddress($id);
        }catch(Exception $e){
           return  null;
        }   
    }
    public static function getInsertId($address,$type)
    {
      return Address::insertGetId([
           'city'=>array_key_exists('city',$address)?$address['city']:null,
           'state'=>array_key_exists('state',$address)?$address['state']:null,
           'landmark'=>array_key_exists('landmark',$address)?$address['landmark']:null,
           'locality'=>array_key_exists('locality',$address)?$address['locality']:null,
           'pin'=>array_key_exists('pin',$address)?$address['pin']:null,
           'lat'=>array_key_exists('lat',$address)?$address['lat']:null,
           'lng'=>array_key_exists('lng',$address)?$address['lng']:null
       ]);
    }

   
}
