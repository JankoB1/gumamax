<?php

namespace Gumamax\Registration;

use App\Http\Requests\Request;
use App\Models\User;
use Delmax\Webapp\Traits\CyrToLatTrait;

class RegisterUserRequest extends Request
{
    use CyrToLatTrait;
    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'register';

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
            'email'      => 'required|email|max:250|unique:ApiDB.user',
            'password'   => 'required|confirmed|min:6|max:30',
            'customer_type_id' => 'in:1,2',
            'company_name' => 'max:64|required_if:customer_type_id,2',
            'tax_identification_number' => ['required_if:customer_type_id,2',  'regex:/^(\d{13}|\d{9})$/']
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

            'email.required' => 'Email je obavezno polje.',
            'email.email' => 'Morate uneti validan email.',
            'email.max' => 'Email može imati maksimum :max karaktera.',
            'email.unique' => "Email već postoji.",

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

        $data = $this->transliterateArray($data);

        if (!isset($data['active'])){
            $data['active'] = 1;
        }

        if (!isset($data['username'])){
            $data['username'] = $data['email'];
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
