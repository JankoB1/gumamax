<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 27.10.2016
 * Time: 6:35
 */

namespace Crm\Requests;


use App\Http\Requests\Request;

class SaveLogoRequest extends Request
{

    public function authorize(){

        return true;

    }

    public function rules(){

        return [
            'logo' => 'required|mimes:png,gif,jpeg,jpg'
        ];
    }
}