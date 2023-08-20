<?php namespace App\Http\Controllers;

use App\Gumamax\BusinessRules\Turnover;
use Illuminate\Http\Response;

class AdminController extends DmxBaseController{


	public function index(){

		return view('admin.dashboard.index');

	}

	public function turnoverAutocompletes()
	{
		return Response::json([
			"partners" => Turnover::getPartnersAutoComplete(),
			"months" => Turnover::getMonthsAutocomplete()
		]);
	}
}
