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

   
}
