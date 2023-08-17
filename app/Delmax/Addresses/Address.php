<?php namespace Delmax\Addresses;

use Delmax\Webapp\Models\City;
use Delmax\Webapp\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.9.2015
 * Time: 7:36
 */


class Address extends Model
{
    use SoftDeletes;

    protected $connection = 'CRM';

    protected $table = 'address';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'address_type_id',
        'addressable_type',
        'addressable_id',
        'default_address',
        'recipient',
        'address',
        'address2',
        'email',
        'phone_number',
        'city_id',
        'city_name',
        'postal_code',
        'country_id',
        'country_name',
        'country_iso_alpha_2',
        'country_iso_alpha_3',
        'additional_info',
        'latitude',
        'longitude',
    ];

    public function addressable(){

        return $this->morphTo();

    }

    public static function make(Array $data) {

        $address = new static ($data);

        return $address;

    }

    public function country(){

        return $this->belongsTo(Country::class, 'country_id');

    }


    public function addressType(){

        return $this->belongsTo(AddressType::class, 'address_type_id');

    }

    public function scopeAccount($query)
    {
        return $query->where('address_type_id', AddressType::ACCOUNT_OWNER);
    }


    public function city(){

        return $this->belongsTo(City::class, 'city_id');

    }

    public function getFullAddressAttribute(){

        return $this->city->city_name.', '. $this->address .' '.$this->address2?$this->address2:'';

    }

    public static function unsetDefaultAddress(Collection $addresses, $newAddress){
        if (isset($newAddress->default_address)&&($newAddress->default_address==1))
        {
            foreach($addresses as $addr){
                $addr->default_address = 0;
                $addr->save();
            }
        }
    }

    public static function apiDatatablesMemberAddresses($memberId){

        $query = DB::connection('CRM')
            ->table('member')
            ->join('address', 'address.addressable_id', '=', 'member.id')
            ->join('address_type', 'address.address_type_id', '=', 'address_type.id')
            ->where('address.addressable_type','=','Crm\\Models\\Member')
            ->where('member.id', '=', $memberId)
            ->select('address.id', 'address.addressable_type', 'address.addressable_id', 'address.address_type_id',
                'address.recipient', 'address.address', 'address.country_name', 'address.postal_code', 'address.city_name',
                'address.email', 'address.phone_number', 'address_type.description as address_type');

        $d = Datatables::of($query);
        $d->addColumn('actions', function($model){
            return '';// view('crm.address.dt-actions', compact('model'));
        });
        return $d->make(true);
    }
}