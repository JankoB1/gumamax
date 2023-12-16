<?php namespace App\Gumamax\Users;

use DB;
use Session;
use Auth;
use Illuminate\Database\Eloquent\Model;

class UserSearchLog extends Model {
	// protected $connection = 'activity_log';
	protected $table = 'user_search_log';
	protected $primaryKey = 'id';
	public $timestamps = false;

	const SEARCH_TYPE_BY_DIMENSION = 1;
	const SEARCH_TYPE_BY_KEYWORDS = 2;
	const SEARCH_TYPE_BY_VEHICLE = 3;

	public static function Log($searchType, $searchQuery, $vehicleCategory, $resQty)
	{
		switch ($searchType) {
			case 'byDimension':
				$st = self::SEARCH_TYPE_BY_DIMENSION;
				break;
			case 'byKeywords':
				$st = self::SEARCH_TYPE_BY_KEYWORDS;
				break;
			case 'byVehicle':
				$st = self::SEARCH_TYPE_BY_VEHICLE;
				break;

			default:
				$st = 0;
				break;
		}

		if(Auth::check()) {
			$uid = Auth::user()->user_id;
		} else {
			$uid = Session::get('guest_id', '');
		}

		$log = new UserSearchLog;
		$log->app_id = 2;
		$log->user_id = $uid;
		$log->search_type = $st;
		$log->search_value = $searchQuery;
		$log->client_ip_address = $_SERVER["REMOTE_ADDR"];
		$log->vehicle_category = $vehicleCategory;
		$log->results_returned = $resQty;

		try {
			$log->save();
			return true;
			// return Response::make(true);
		} catch (Exception $e) {
			// return Response::make($e);
			return false;
		}
	}
}