<?php namespace Delmax\Cart\Requests;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.9.2015
 * Time: 8:54
 */

use App\Http\Requests\Request;

class CartEmptyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->guest()){
            $activeUid = session(config('dmxcart.cookie_uid_name'));
            $uid = $this->get('uid');
            return ($activeUid==$uid);
        }

        return true;

    }

    public function rules(){
        return [
            'uid'           => 'required'
        ];
    }
}