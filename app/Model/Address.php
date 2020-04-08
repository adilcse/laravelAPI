<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Address extends Model
{
    protected $table='address';
    public static function getAddress($address_id)
    {
     return DB::table('address')
        ->join('indian_states','address.state_id','=','indian_states.id')
        ->select( 'address.name as delivery_name',
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
    }
}