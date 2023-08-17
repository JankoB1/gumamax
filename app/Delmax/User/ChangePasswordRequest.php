<?php
namespace Delmax\User;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.9.2015
 * Time: 0:59
 */
use App\Http\Requests\Request;

class ChangePasswordRequest extends Request
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

    protected function getRedirectUrl(){

        return route('profile.show',[auth()->user()->user_id, 'tab'=>'password']);

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

    public function all($keys = null){

        $data = parent::all($keys);

        if (!isset($data['user_id'])){

            $data['user_id'] = auth()->user()->user_id;

        }

        return $data;

    }
}