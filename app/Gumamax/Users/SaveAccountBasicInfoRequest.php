<?php namespace App\Gumamax\Users;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 15.9.2015
 * Time: 22:20
 */

use App\Http\Requests\Request;

use App\Models\User;

class SaveAccountBasicInfoRequest extends Request
{

    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    protected function getRedirectUrl(){

        return route('profile.show',[auth()->user()->user_id,'tab'=>'account']);

    }

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
            'last_name'  => 'required|max:250',
            'first_name' => 'required|max:250',
            'customer_type_id' => 'in:1,2',
            'company_name' => 'max:64|required_if:customer_type_id,'.User::IS_COMPANY,
            'tax_identification_number' => ['required_if:customer_type_id,'.User::IS_COMPANY]
        ];
    } 

    public function messages(){
        return [
            'first_name.required' => 'Ime je obavezno polje.',
            'first_name.min' => 'Ime mora imati minimalno :min karaktera.',
            'first_name.max' => 'Ime može imati maksimum :max karaktera.',
            'first_name.regex' => "Unesite ispravno ime.",

            'last_name.required' => 'Prezime je obavezno polje.',
            'last_name.min' => 'Prezime mora imati minimalno :min karaktera.',
            'last_name.max' => 'Prezime može imati maksimum :max karaktera.',
            'last_name.regex' => "Unesite ispravno prezime.",

            'phone.required' => 'Telefon je obavezno polje.',
            'phone.regex' => 'Neispravan format telefonskog broja.',
            'phone.max' => 'Telefon može imati najviše :max cifre.',

            "company_name.required" => "Naziv preduzeća je obavezno ako se registrujete kao pravno lice",
            "company_name.max" => "Naziv preduzeća može imati najviše :max karaktera",

            "tax_identification_number.required" => 'PIB je obavezan ako se registrujete kao pravno lice',
            "tax_identification_number.regex" => 'Neispravan format za PIB',

        ];
    }

    /**
     * Overriding original all() because of username
     * @return array
     */
    public function all($keys = null){

        $data = parent::all($keys);

        if (!isset($data['user_id'])){

            $data['user_id'] = auth()->user()->user_id;

        }

        if (!isset($data['receive_newsletter'])){
            $data['receive_newsletter'] = 0;
        }

        if ((!isset($data['typ_id']))||($data['typ_id']=='')){
            $data['typ_id'] = 0;
        }

        if ((!isset($data['vin']))||($data['vin']=='')) {
            $data['vin'] = null;
        }

        if ((!isset($data['engine_code']))||($data['engine_code']=='')){
            $data['engine_code'] = null;
        }

        return $data;
    }
}
