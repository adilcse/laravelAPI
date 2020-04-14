<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Address extends Model
{
    protected $table='address';
    public static function getAddress($address_id)
    {
     return Address::select( 'address.id',
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
    public static function store($address,$type)
    {
        return Address::insertGetId([
            'name'=>array_key_exists('name',$address)?$address['name']:null,
            'number'=>array_key_exists('number',$address)?$address['number']:null,
            'alternate'=>array_key_exists('alternate',$address)?$address['alternate']:null,
            'address'=>array_key_exists('address',$address)?$address['address']:null,
             'city'=>array_key_exists('city',$address)?$address['city']:null,
             'state'=>array_key_exists('state',$address)?$address['state']:null,
             'landmark'=>array_key_exists('landmark',$address)?$address['landmark']:null,
             'locality'=>array_key_exists('locality',$address)?$address['locality']:null,
             'pin'=>array_key_exists('pin',$address)?$address['pin']:null,
             'lat'=>array_key_exists('lat',$address)?$address['lat']:null,
             'lng'=>array_key_exists('lng',$address)?$address['lng']:null,
             'address_type'=>$type
         ]);
    }
}