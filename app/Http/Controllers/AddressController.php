<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Address;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers\UserController;

class AddressController extends Controller
{
    public static function getUserAddress($user)
    {
        $address_id=$user['address_id'];
        if($address_id){
        try{
            $address=(array)Address::getAddress($address_id);
            $userData=array_merge($user,$address);
           // var_dump($userData);
           return $userData;
        }catch(Exception $e){
            var_dump($e);
           return  $user;
        }
    }else{
       return $user;
    }   
    }
    public static function getInsertId($address,$type)
    {
      return Address::insertGetId([
           'city'=>array_key_exists('city',$address)?$address['city']:null,
           'state_id'=>1,
           'landmark'=>array_key_exists('landmark',$address)?$address['landmark']:null,
           'locality'=>array_key_exists('locality',$address)?$address['locality']:null,
           'pin'=>array_key_exists('pin',$address)?$address['pin']:null,
           'lat'=>array_key_exists('lat',$address)?$address['lat']:null,
           'lng'=>array_key_exists('lng',$address)?$address['lng']:null
       ]);
    }

   
}
