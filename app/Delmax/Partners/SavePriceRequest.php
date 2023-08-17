<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 8.10.2016
 * Time: 13:00
 */

namespace Delmax\Partners;


use App\Http\Requests\Request;

class SavePriceRequest extends Request
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
            'partner_id'        =>  'required|integer',
            'product_id'        =>  'required|integer',
            'price_with_tax'    =>  'numeric'
        ];
    }

    public function messages()
    {
        return [
            'price_with_tax.numeric'    =>  'Cena mora biti uneta kao broj. Koristitre decimalnu tačku "." za brojeve sa decimalama'
        ];
    }

    public function all(){
        $data = parent::all();
        /**
         * Presrecemo request poslat iz x-editable kontrole u "pk" su upisane vrednosti potrebne za identifikaciju cene
         */
        $data['partner_id']     = $data['pk']['partner_id'];
        $data['product_id']     = $data['pk']['product_id'];
        $data['id']             = $data['pk']['id'];
        $data['price_with_tax'] = $data['value'];

        return $data;
    }
}