<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 11.10.2016
 * Time: 3:42
 */

namespace Gumamax\Partners;


use App\Http\Requests\Request;
use Delmax\Webapp\Traits\CyrToLatTrait;

class SavePartnerRequest extends Request
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
            'description' => 'required|max:32',
            'description2' => 'max:32',
            'tax_identification_number' => ['required','regex:/^(\d{13}|\d{9})$/'],
            'city_id'   => ['required','integer','regex:/^\d{5}/'],
            'address'   => 'required|max:48',
            'phone'     => 'required|max:32',
            'email'     => 'required|email|max:64',
            'web'       => 'max:64',
            'longitude' => 'numeric',
            'latitude'  => 'numeric'
        ];
    }

    public function all($keys = null){

        $data = parent::all($keys);

        $data = $this->transliterateArray($data);

        return $data;
    }

}