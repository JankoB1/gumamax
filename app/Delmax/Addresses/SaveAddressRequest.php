<?php namespace Delmax\Addresses;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.9.2015
 * Time: 12:13
 */

use App\Http\Requests\Request;
use Delmax\Webapp\Models\City;
use Delmax\Webapp\Models\Country;

class SaveAddressRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "address_type_id"   => "required|in:1,2,3,4,5",
            "addressable_type"  => "required|max:250",
            "addressable_id"    => "required|max:255",
            "recipient"         => "required|max:250",
            "address"           => "required|max:250",
            "address2"          => "max:45",
            "city_id"           => "required|integer",
            "phone_number"      => ["max:20","regex:/^(\()*(\+)*\d+(\))*(\-|\/|\s|)\d+(\-|\s)*\d+(\-|\s)*\d+(\-|\s)*\d+$/"],
            "email"             => "email|max:250",
            "additional_info"   => "max:250",
            "latitude"          => "numeric",
            "longitude"         => "numeric",
        ];
    }

    public function all($keys = null){
        $data = parent::all($this);

        $city = City::find($data['city_id']);
        $country = Country::find($city->country_id);

        $data['city_name'] = $city->city_name;
        $data['postal_code'] = $city->postal_code;

        $data['country_id'] = $country->counrty_id;
        $data['country_name'] = $country->description;
        $data['country_iso_alpha_2'] = $country->iso_alpha_2;
        $data['country_iso_alpha_3'] = $country->iso_alpha_3;

        return $data;
    }

}