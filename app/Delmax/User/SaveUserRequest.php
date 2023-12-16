<?php
namespace Delmax\User;


use App\Http\Requests\Request;


/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.9.2015
 * Time: 18:08
 */


class SaveUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       if (auth()->user()->hasRole(['superadmin'])){
            return true;
        }

        $user_id = $this->route('user_id');

        if (($user_id==auth()->user()->user_id)){

            return true;
        }

        return false;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required|max:64',
            'last_name'     => 'required|max:64',
            'phone_number'  => 'required|max:64',

        ];
    }

}