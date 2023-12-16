<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.10.2016
 * Time: 18:35
 */

namespace Crm\Requests;


use App\Http\Requests\Request;

class SaveMemberInformationRequest extends Request
{

    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'member_id'=>'required',
            'information_id'=>'required'
        ];
    }

    public function all($keys = null) {

        $data = parent::all();
        $pk                     = $data['pk'];
        $data['id']             = $pk['id'];
        $data['member_id']      = $pk['member_id'];
        $data['information_id'] = $pk['information_id'];
        $data['datatype']       = $pk['datatype'];

        if ($data['datatype'] == 'integer') {
            $data['value_integer'] = (int) $data['value'];
        } else if ($data['datatype'] == 'decimal') {
            $data['value_decimal'] = floatval($data['value']);
        }

        if (isset($data['lookup_value'])) {
            $data['value_text'] = $data['lookup_value']['text'];
        } else {
            $data['value_text'] = $data['value'];
        }

        return $data;
    }

}