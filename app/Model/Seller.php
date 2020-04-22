<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Seller extends Model
{
	/**
   * return all sellers available in the database;
   */
	protected $table='sellers';
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
		$value=Seller::join('address','sellers.address_id','=','address.id')
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
						'address.state',
						'address.landmark',
						'address.locality')
				->having('distance','<',$dist)
				->where('sellers.current_status','=','ACTIVE')
				->limit($lmt)
				->get();
		return $value;
	}
}
