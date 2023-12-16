<?php namespace Delmax\User;
use Delmax\Addresses\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 1.10.2015
 * Time: 6:42
 */


class Guest extends Model
{
    protected $connection = 'ApiDB';
    protected $table = 'guest';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'ip_address', 'shipping_to_partner_id', 'shipping_to_address_id'];

    public $incrementing = false;

    public static function make(){
        return new static (['id'=>session()->getId(), 'ip_address'=>getIpAddress()]);
    }

    public static function getActiveGuest(){

        $guest = Guest::find(session()->getId());

        if (!$guest){
            $guest = Guest::make();
            $guest->save();
        }

        return $guest;
    }

    public function addresses(){

        return $this->morphMany(Address::class, 'addressable');

    }

    public function addAddress(Address $newAddress){
        Address::unsetDefaultAddress($this->addresses, $newAddress);
        return $this->addresses()->save($newAddress);
    }

}