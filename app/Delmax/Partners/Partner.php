<?php namespace Delmax\Partners;

use Delmax\Addresses\Address;
use Delmax\Addresses\AddressType;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 21.9.2015
 * Time: 23:35
 */


class Partner extends Model
{
    protected $connection = 'delmax_gumamax';

    protected $table = 'partner';

    protected $primaryKey = 'partner_id';

    public function addresses(){

        return $this->morphMany(Address::class, 'addressable');

    }

    public function addAddress(Address $address){

        return $this->addresses()->save($address);

    }

    public function addGumamaxAddress(Address $address){

        $address->address_type_id=AddressType::GUMAMAX_PARTNER;

        return $this->addresses()->save($address);

    }

    public function gumamaxAddress(){

        return $this->addresses()->where('address_type_id', AddressType::GUMAMAX_PARTNER);

    }

    public static function findBySubdomain($subdomain)
    {
        return self::where('subdomain', $subdomain)->first();
    }


}