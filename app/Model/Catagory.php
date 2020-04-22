<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * Catagory model handle CURD  on catagory table
 */
class Catagory extends Model
{
	/** 
	 * get all catagories sorted by name
	 */
	public static function getAllCatagory(){
			$value=DB::table('catagories')->orderBy('name', 'asc')->get();
			return $value;
		}
}

