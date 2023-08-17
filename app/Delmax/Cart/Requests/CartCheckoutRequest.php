<?php namespace Delmax\Cart\Requests;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.10.2015
 * Time: 19:10
 */
use App\Http\Requests\Request;

class CartCheckoutRequest extends Request
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

    public function rules(){
        return [
            'cart_id'   => 'required'
        ];
    }
}