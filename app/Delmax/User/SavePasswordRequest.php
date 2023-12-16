<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.10.2016
 * Time: 10:31
 */

namespace Delmax\User;

use App\Http\Requests\Request;

class SavePasswordRequest extends Request
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
            'password_old'  => 'required|check_pwd:password|max:30',
            'password'      => 'required|confirmed|max:30|different:password_old',

        ];
    }

    public function messages(){
        return [
            'check_pwd'=>'Stara lozinka nije tačna',
            'different'=> 'Nova lozinka mora biti različita od stare'
        ];
    }

}