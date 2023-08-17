<?php namespace Delmax\Shipping;

use Illuminate\Support\Facades\DB;
use stdClass;

class Courier {

	public static function getAll()
	{
		return DB::table('courier')->get();
	}

	public static function getPriceBase()
	{
		return DB::table('courier_price_base')->get();
	}

	public static function getPriceList()
	{
		return DB::select("SELECT
							  cpl.*,
							  cs.name AS service,
							  c.name AS courier_name,
							  c.courier_id AS courier
							FROM courier_price_list cpl
							LEFT JOIN courier_service cs ON cs.courier_service_id=cpl.courier_service_id
							LEFT JOIN courier c ON c.courier_id=cs.courier_id
							WHERE cpl.deleted_at IS NULL");
	}

	public static function getServices()
	{
		return DB::table('courier_service')->get();
	}

	public static function getById($id)
	{
		if($id=='add') {
			$c = new stdClass;
			$c->courier_id = '';
			$c->name = '';
			$c->is_active = 0;
			$c->merchant_id = 0;
			return $c;
		} else {
			return DB::table('courier')->where('courier_id','=',$id)->first();
		}
	}

	public static function getPriceBaseById($id)
	{
		if($id=='add') {
			$c = new stdClass;
			$c->courier_price_base_id = '';
			$c->name = '';
			return $c;
		} else {
			return DB::table('courier_price_base')->where('courier_price_base_id','=',$id)->first();
		}
	}

	public static function getServiceById($id)
	{
		if($id=='add') {
			$c = new stdClass;
			$c->courier_service_id = -1;
			$c->name = '';
			$c->courier_id = '';
			$c->courier_price_base_id = '';
			$c->erp_service_id = '';
			$c->service_id = '';

			return $c;
		} else {
			return DB::table('courier_service')->where('courier_service_id','=',$id)->first();
		}
	}


	public static function getPriceListById($id)
	{
		if($id=='add') {
			$c = new stdClass;
			$c->courier_price_list_id = -1;
			$c->courier_service_id = 0;
			$c->from_value = '';
			$c->price_incl_tax = '';
			$c->to_value = '';
			return $c;
		} else {
			return DB::table("courier_price_list")->where('courier_price_list_id','=',$id)->first();
		}
	}

	public static function insert($data)
	{
		return DB::table('courier')->insert(array(
			"courier_id" => $data['courier_id'],
			"name" => $data['name'],
			"is_active" => isset($data['is_active']) ? 1 : null,
			"merchant_id" => isset($data['merchant_id']) ? $data['merchant_id'] : 0
		));
	}

	public static function update($id,$data)
	{
		return DB::table('courier')
			->where('courier_id','=',$id)
			->update(array(
				"name" => $data['name'],
				"is_active" => isset($data['is_active']) ? 1 : null,
				"merchant_id" => isset($data['merchant_id']) ? $data['merchant_id'] : 0
			));
	}

	public static function insertPriceBase($data)
	{
		return DB::table('courier_price_base')->insert(array(
			"courier_price_base_id" => $data['courier_price_base_id'],
			"name" => $data['name']
		));
	}

	public static function updatePriceBase($id,$data)
	{
		return DB::table('courier_price_base')
			->where('courier_price_base_id','=',$id)
			->update(array(
				"name" => $data['name']
			));
	}


	public static function insertPriceList($data)
	{
		return DB::table('courier_price_list')->insert(array(
			"courier_service_id" => $data['courier_service_id'],
			"from_value" => $data['from_value'],
			"to_value" => $data['to_value'],
			"price_incl_tax" => $data['price_incl_tax']
		));
	}

	public static function updatePriceList($id,$data)
	{
		return DB::table('courier_price_list')
			->where('courier_price_list_id','=',$id)
			->update(array(
				"courier_service_id" => $data['courier_service_id'],
				"from_value" => $data['from_value'],
				"to_value" => $data['to_value'],
				"price_incl_tax" => $data['price_incl_tax'],
				"updated_at" => date('Y-m-d H:i:s')
			));
	}

	public static function insertService($data)
	{
		return DB::table('courier_service')->insert(array(
			"courier_id" => $data['courier_id'],
			"courier_price_base_id" => $data['courier_price_base_id'],
			"erp_service_id" => $data['erp_service_id'],
			"name" => $data['name'],
			"service_id" => $data['service_id']
		));
	}

	public static function updateService($id,$data)
	{
		return DB::table('courier_service')
			->where('courier_service_id','=',$id)
			->update(array(
				"courier_id" => $data['courier_id'],
				"courier_price_base_id" => $data['courier_price_base_id'],
				"erp_service_id" => $data['erp_service_id'],
				"name" => $data['name'],
				"service_id" => $data['service_id']
			));
	}


	public static function save($data)
	{
		if(strtoupper($data['cid'])=='ADD') {
			self::insert($data);
		} else {
			self::update($data['courier_id'], $data);
		}
	}


	public static function savePriceBase($data)
	{
		if(strtoupper($data['cid'])=='ADD') {
			self::insertPriceBase($data);
		} else {
			self::updatePriceBase($data['courier_price_base_id'], $data);
		}
	}

	public static function savePriceList($data)
	{
		if(strtoupper($data['courier_price_list_id'])==-1) {
			self::insertPriceList($data);
		} else {
			self::updatePriceList($data['courier_price_list_id'], $data);
		}
	}

	public static function saveService($data)
	{
		if(strtoupper($data['courier_service_id'])==-1) {
			self::insertService($data);
		} else {
			self::updateService($data['courier_service_id'], $data);
		}
	}
}