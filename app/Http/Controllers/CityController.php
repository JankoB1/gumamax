<?php namespace App\Http\Controllers;

use DB;
use Input;

use App\Gumamax\City;
use Illuminate\Http\Client\Request;

class CityController extends DmxBaseController {

    public function cities($fmt=''){
        return (new City)->getCities($fmt, request()->get('term'));
    }
}