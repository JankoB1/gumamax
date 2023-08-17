<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.10.2016
 * Time: 0:09
 */

namespace Delmax\User;

use App\Http\Requests\Request;
use App\Models\User;

class SaveCustomerRequest extends Request
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
            'customer_type_id' => 'required|in:1,2',
            'company_name' => 'max:64|required_if:customer_type_id,2',
            'tax_identification_number' => ['required_if:customer_type_id,2',  'regex:/^(\d{13}|\d{9})$/']

        ];
    }

    public function all($keys = null){

        $data = parent::all();

        if ($data['customer_type_id']==User::IS_PERSON){
            $this->company_name=null;
            $this->tax_identification_number=null;
        }

        return $data;
    }

}