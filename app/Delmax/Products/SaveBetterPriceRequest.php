<?php
/**
 * Created by PhpStorm.
 * User: Bane
 * Date: 13.10.2015
 * Time: 9:12
 */

namespace Delmax\Products;


use App\Http\Requests\Request;

class SaveBetterPriceRequest extends Request
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
            'product_id' => 'required',
            'customer_name' => 'required|max:250',
            'customer_email' => 'required|email',
            'shop_name' => 'required|max:250',
            'shop_phone_number' => ['required','max:250','regex:/^(\()*(\+)*\d+(\))*(\-|\/|\s|)\d+(\-|\s)*\d+(\-|\s)*\d+(\-|\s)*\d+$/'],
            'price' => 'required|numeric'
        ];
    }

}