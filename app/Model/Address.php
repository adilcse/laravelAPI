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
        ->select( 'address.id',
                  'address.name',
                  'address.pin',
                  'address.lat',
                  'address.lng',
                  'address.number',
                  'address.address',
                  'address.formatted_address',
                  'address.city',
                  'address.state',
                  'address.landmark',
                  'address.locality')
          ->where('address.id',$address_id)
          ->first();
    }
}