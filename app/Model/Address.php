<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
/**
 * address modal calls database address table hanlde database CURD operation
 */
class Address extends Model
{
    protected $table='address';
    /**
     * get address from database by address_id
     * @param address_id
     * @return address
     */
    public static function getAddress($address_id)
    {
        $address = Address::select( 'address.id',
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
            ->find($address_id);
        if($address)
            return (array)$address->original;
        else 
            return false;
    }
    /**
     * store new address to database
     * @param address
     * @param USER,SELLER
     * @return id of inserted address
     */
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