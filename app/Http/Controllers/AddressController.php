<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Address;
use Illuminate\Database\QueryException;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    public static function saveAddress($address,$type)
    {
        try{
     return Address::store($address,$type);
        }
        catch(QueryException $e){
            return null;
        }
    }
    public static function deleteAddress($id)
    {
       try{
           Address::where('id',$id)->delete();
           return true;
       } 
       catch (QueryException $e){

           return false;
       }
    }
    public static function updateAddress(Request $request)
    {
        $address=(array)$request->input('json');
        $user=Auth::user();
        try{
             $id = AddressController::saveAddress($address);
             UserController::updatAddressId($user->id,$id);
             return response(['status'=>'success'],200);
        }
        catch(QueryException $e){
            return response(['error'=>$e],403);
                }
      
    }
   

   
}
