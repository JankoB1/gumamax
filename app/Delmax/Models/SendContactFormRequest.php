<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 3.10.2016
 * Time: 5:05
 */

namespace Delmax\Models;


use App\Http\Requests\Request;

class SendContactFormRequest extends Request
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
            'name'      =>  'required|max:250',
            'email'     =>  'required|max:250',
            'message'   =>  'required|max:1024',
            'g-recaptcha-response' => 'required|captcha'
        ];
    }
}
