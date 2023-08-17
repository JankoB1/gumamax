<?php namespace Delmax\Cart\Requests;

use App\Http\Requests\Request;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 23.9.2015
 * Time: 0:03
 */


class CartAddItemRequest extends Request
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
            'merchant_id'   => 'required',
            'product_id'    => 'required|integer|min:1',
            'qty'           => 'required|numeric|min:0.0001'
        ];
    }
}