<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.9.2016
 * Time: 0:45
 */

namespace Gumamax\Partners;


use App\Http\Requests\Request;
use Delmax\Webapp\Traits\CyrToLatTrait;

class RegisterPartnerRequest extends Request
{
    use CyrToLatTrait;

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
            'name' => 'required|max:32',
            'department' => 'max:32',
            'tax_identification_number' => array('regex:/^(\d{13}|\d{9})$/'),
            'first_name' => 'required|max:64',
            'last_name' => 'required|max:64',
            'city_id' => 'required|integer|regex:/^\d{5}/',
            'address' => 'required|max:48',
            'phone' => 'required|max:32',
            'email' => 'required|email|unique:ApiDB.user,email|max:64',
            'web_address' => 'max:64',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
            'is_installer' => 'required|in:0,1,2'
        ];
    }

    public function all($keys = null){

        $data = parent::all($keys);

        $data = $this->transliterateArray($data);

        return $data;
    }
}