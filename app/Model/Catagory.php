<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Catagory extends Model
{
    public static function getAllCatagory(){
        $value=DB::table('catagories')->orderBy('name', 'asc')->get();
        return $value;
      }
}

