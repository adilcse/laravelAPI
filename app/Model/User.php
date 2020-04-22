<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class User extends Model
{
	public static function getuserData(){
		$value=DB::table('users')->orderBy('id', 'asc')->get();
		return $value;
	}
	/**
	   * stores new user data in database
	   */
	public static function store($userData){
		return DB::table('users')->insert($userData);
	}  
	/**
	 * get user by uid
	 */
	public static function getByUid($uid)
	{
		$user= DB::table('users')
			->select('id','uid','name','email','address_id')
			->where('users.uid',$uid)->first();
		if($user){
		$address_id=$user->{'address_id'};
		if($address_id){
			$address= DB::table('address')
				->join('indian_states','address.state_id','=','indian_states.id')
				->select('address.name as delivery_name',
						'address.pin',
						'address.lat',
						'address.lng',
						'address.number',
						'address.city',
						'address.state_id',
						'indian_states.name as state',
						'address.landmark',
						'address.locality')
				->where('address.id',$address_id)
				->first();
			return array_merge((array)$user,(array)$address,['error'=>false]);
			}
			else{
				return array_merge((array)$user,['error'=>false]);
			}
		}
		else{
			return ['error'=>'user not found'];
		}
	}
}

