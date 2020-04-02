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
}

