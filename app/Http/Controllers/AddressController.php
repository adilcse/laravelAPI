<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Address;
use Illuminate\Database\QueryException;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
class AddressController extends Controller
{
	/**
	 * get user's address by user address id
	 * @param {*} id
	 */
	public static function getUserAddress($id)
	{
		try{
			return Address::getAddress($id);
		}
		catch(Exception $e){
			return  null;
		}
	}


	/**
	 * save address to address table and return new id of the inserted address
	 */
	public static function saveAddress($address,$type='USER')
	{
		// check if latitude and longitude is valid
		if(!is_double($address['lat'])){
			unset($address['lat']);
		}
		if(!is_double($address['lng'])){
			unset($address['lng']);
		}
		try{
			return Address::store($address,$type);
		}
		catch(QueryException $e){
			return null;
		}
	}


	/**
	 * delete an address which is not assigned to any user or order
	 */
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


	/**
	 * update address API. user can edit ite address by this function
	 */
	public static function updateAddress(Request $request)
	{
		$address=(array)json_decode($request->input('json'));
		$user=Auth::user();
		$oldAddress=$user->addressId;
		try{
			//save new address in database
			$id = AddressController::saveAddress($address);
			if($id){
				//update addres id in user's table
				UserController::updateAddressid($user->id,$id);
				//try to delete the old address if it is not linked with any order
				$delete=AddressController::deleteAddress($oldAddress);
				return response(['status'=>'success','delete'=>$delete],200);
			}else{
				return response(['error'=>'address can not updated'],200);
			}
		}
		catch(QueryException $e){
			return response(['error'=>$e],403);
		}
	}
	
}
