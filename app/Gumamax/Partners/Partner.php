<?php namespace Gumamax\Partners;


use Delmax\Attachments\Attachment;
use Delmax\Models\PaymentMethod;
use Delmax\Webapp\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Facades\Datatables;


class Partner extends Model {

	protected $connection = 'CRM';

	protected $table = 'partner_gmx';

	protected $primaryKey = 'partner_id';

	public function city(){

		return $this->hasOne(City::class, 'city_id', 'city_id');

	}

	public function about(){
		return $this->hasOne(PartnerAbout::class, 'partner_id', 'partner_id');
	}


	public function attachments(){

		return $this->morphMany(Attachment::class, 'attachable');

	}

	public function logo(){

		return $this->hasOne(PartnerLogo::class, 'partner_id');

	}

	public function cover(){

		return $this->hasOne(PartnerCover::class, 'partner_id');

	}

	public function openingHours(){

		return $this->hasMany(OpeningHours::class, 'partner_id', 'partner_id');

	}

	public function openingHoursDisplay(){

		$sql = "
			SELECT
				dow.id as day_of_week_id,
				dow.description as day_description,
		    	CONCAT(COALESCE(concat(cpoh.description,' '),''),
		    		if(cpoh.start_time is null, '',	concat(TIME_FORMAT(cpoh.start_time,'%H:%i'),'-',TIME_FORMAT(cpoh.end_time,'%H:%i'))),'') AS hours,
				TIME_FORMAT(cpoh.start_time,'%H:%i') AS start_time,
				TIME_FORMAT(cpoh.end_time,'%H:%i') AS end_time,
				cpoh.description
			FROM day_of_week dow
			left JOIN partner_opening_hour cpoh ON cpoh.day_of_week_id = dow.id and cpoh.deleted_at is null and cpoh.partner_id=?
            ORDER BY dow.order_index
            ";

		return DB::connection('CRM')->select($sql, [$this->partner_id]);

	}

	public function paymentMethods()
	{
		return $this->belongsToMany(PaymentMethod::class, 'delmax_gumamax.partner_payment_method', 'partner_id', 'payment_method_id');
	}

	public function editPaymentMethodsDisplay()
	{
		$sql = 'select pm.payment_method_id, pm.description, ppm.payment_method_id as checked, pm.is_active
				from payment_method pm
					left join partner_payment_method ppm on ppm.payment_method_id=pm.payment_method_id
				where pm.deleted_at is null and ppm.deleted_at is null and ppm.partner_id=?
				order by pm.order_index';
		return DB::connection('CRM')->select($sql, [$this->partner_id]);
	}


	/**
	 *   get partner_gmx list
	 */
	public static function getPartners($fmt)
	{
		$ps = DB::connection('CRM')->table('partner_gmx')
			->leftJoin('city','city.city_id','=','partner_gmx.city_id')
			->leftJoin('partner_about_gmx','partner_about_gmx.partner_id','=','partner_gmx.partner_id')
			->select([
				"partner_gmx.partner_id",
				"partner_gmx.name",
				"partner_gmx.department",
				"partner_gmx.tax_identification_number",
				"partner_gmx.email",
				"partner_gmx.phone",
				"partner_gmx.fax",
				"partner_gmx.web_address",
				"partner_gmx.address",
				"partner_gmx.city_id",
				"partner_gmx.country_id",
				"partner_gmx.longitude",
				"partner_gmx.latitude",
				"partner_gmx.avg_overall_rating",
				"partner_gmx.created_at",
				"partner_gmx.approved_at",
				"partner_gmx.deleted_at",
				"partner_gmx.rejected_at",
				"partner_gmx.is_installer",

				"city.city_name",
				"city.postal_code",

				"partner_about_gmx.partner_about_gmx_id",
				"partner_about_gmx.short_text",
				"partner_about_gmx.long_text",
				"partner_about_gmx.is_deleted",
				"partner_about_gmx.contact_person_firstname",
				"partner_about_gmx.contact_person_lastname",
				"partner_about_gmx.car_max_rim",
				"partner_about_gmx.suv_max_rim",
				"partner_about_gmx.van_max_rim",
				"partner_about_gmx.bike_max_rim",
				"partner_about_gmx.truck_max_rim",
				"partner_about_gmx.place_of_delivery",
				"partner_about_gmx.online_scheduling",
				"partner_about_gmx.scheduling_period AS vreme_zakazivanja",
				"partner_about_gmx.comment",
				"partner_about_gmx.message",
				"partner_about_gmx.google_local_service",
				"partner_about_gmx.motorcycle_wheel_price_calc",
				"partner_about_gmx.motorcycle_wheel_balance",
				"partner_about_gmx.number_of_platforms",
				"partner_about_gmx.number_of_platforms_b",
				"partner_about_gmx.number_of_platforms_d",
				"partner_about_gmx.mobile_service_radius",
				"partner_about_gmx.delivery_radius",
				"partner_about_gmx.free_installation"
			]);

		switch ($fmt) {
			case 'json':
				return Response::json($ps->orderBy('partner_about_gmx.turnover', 'DESC')->orderBy('partner_gmx.name')->get());
				break;
			case 'xml':
				return 'TODO: xml';
				// find & install XML formater package + convert to XML
				break;
			case 'datatables':
                // TODO: Datatables for L5: "yajra/laravel-datatables-oracle": "~4.0"
				return 'TODO: add bllim/datatables + return datatables json';
				// return Datatables::of($ps)->make();
				break;

			default:
				return $ps->orderBy('partner_about_gmx.turnover', 'DESC')->orderBy('partner_gmx.name')->get();
				break;
		}
	}

    /**
     * Vraca osnovne podatke o partneru ( id, naziv, adresu, mest, post. broj )
     * @param $partner_id
     * @return mixed
     */
    public static function getShortInfo($partner_id)
    {
        return DB::table('partner_gmx')
                ->join('city', 'partner_gmx.city_id', '=', 'city.city_id')
                ->select('partner_gmx.partner_id', 'partner_gmx.name', 'partner_gmx.department', 'partner_gmx.address', 'partner_gmx.is_installer', 'city.city_id', 'city.postal_code', 'city.city_name', 'partner_gmx.phone', 'partner_gmx.email')
                ->where('partner_gmx.partner_id','=',(int)$partner_id)
                ->first();
    }

    public static function getName($partnerId)
    {
        $r = DB::table('partner_gmx')
            ->where('partner_id','=', $partnerId)
            ->first();
        if (! is_null($r)){
            return $r->name." ".$r->department;
        } else
            return null;
    }

    /**
     * 		Partner edit
     */
    public static function savePartnerMobileService($partner_id)
    {
		$car = Input::get('mobile_service_car') ? 1:0;
		$suv = Input::get('mobile_service_suv') ? 1:0;
		$van = Input::get('mobile_service_van') ? 1:0;
		$bike = Input::get('mobile_service_bike') ? 1:0;
		$truck = Input::get('mobile_service_truck') ? 1:0;
		$distance = Input::get('mobile_service_radius');

    	if($distance=='-' || $distance==''){ $distance=null; }
    	$res = DB::table('partner_about_gmx')
    		->where('partner_id','=',$partner_id)
    		->whereNull('is_deleted')
    		->update(array(
    			"mobile_service_car" => $car,
    			"mobile_service_suv" => $suv,
    			"mobile_service_van" => $van,
    			"mobile_service_bike" => $bike,
    			"mobile_service_truck" => $truck,
    			"mobile_service_radius" => $distance
    	));
    	return ["status"=>true, "errors"=>[]];
    }



    public static function saveServiceOther( $partner_id, $maxrim_auto, $maxrim_4x4, $maxrim_kombi, $maxrim_motor, $maxrim_kamion )
    {
    	if($maxrim_auto  =='-'){ $maxrim_auto=null; }
    	if($maxrim_4x4   =='-'){ $maxrim_4x4=null; }
    	if($maxrim_kombi =='-'){ $maxrim_kombi=null; }
    	if($maxrim_motor =='-'){ $maxrim_motor=null; }
    	if($maxrim_kamion=='-'){ $maxrim_kamion=null; }
    	DB::table('partner_about_gmx')
    		->where('partner_id','=',$partner_id)
    		->update(array(
    			"car_max_rim" => $maxrim_auto,
    			"suv_max_rim" => $maxrim_4x4,
    			"van_max_rim" => $maxrim_kombi,
    			"bike_max_rim"=> $maxrim_motor,
    			"truck_max_rim"=> $maxrim_kamion
    		));
    	return ["status"=>true, "errors"=>[]];
    }

    public static function saveDeliveryRadius($crmpaid,$partner_id,$delivery_radius)
    {
    	if($delivery_radius=='-'){ $delivery_radius=null; }
    	DB::table('partner_about_gmx')
    		->where('id','=',$crmpaid)
    		->where('partner_id','=',$partner_id)
    		->whereNull('is_deleted')
    		->update(array(
    			"delivery_radius" => $delivery_radius
    	));
    	return ["status"=>true, "errors"=>[]];
    }


	public function savePartnerContact($id)
	{
		$data = Input::all();
    	foreach ($data as $k=>$v) {
    		$data[$k] = str_replace($this->cyr, $this->lat, $v);
    	}
		$errors = new MessageBag();
		if($old = Input::old('errors')){
			$errors = $old;
		}
		$data['errors'] = $errors;

		$f = ['telefon','email','fax','web_adresa','kontakt_osoba_prezime','kontakt_osoba_ime'];
		$valRules = $this->getValidationRules($f);
		$valMsgs = $this->getValidationMessages($f);

		$validator = Validator::make($data, $valRules, $valMsgs);
		if($validator->passes()){
			try {
				DB::transaction(function() use ($data,$id) {
					DB::table('partner_gmx')
						->where('partner_id','=',$id)
						->update(array(
							"phone" => $data['telefon'],
							"fax" => $data['fax'],
							"email" => $data['email'],
							"web_address" => $data['web_adresa']
						));
					DB::statement(" INSERT INTO partner_about_gmx
										(partner_id,
										contact_person_firstname,
										contact_person_lastname)
									VALUES ({$id},
										'{$data['kontakt_osoba_ime']}',
										'{$data['kontakt_osoba_prezime']}')
									ON DUPLICATE KEY UPDATE
										contact_person_firstname = '{$data['kontakt_osoba_ime']}',
										contact_person_lastname = '{$data['kontakt_osoba_prezime']}'");
				});
			} catch (Exception $e) {
				$err[] = "Došlo je do greške".$e;
				$resp = ["status" => false, "errors" => $err];
				return $resp;
			}
			$resp = ["status" => true, "errors" => []];
			return $resp;
		}
		$resp = [
			"status"=> false,
			"errors"=>$validator->messages()->toArray()
		];
		return $resp;
	}

	public function savePartnerWorkingHours($id)
	{
		$data = Input::all();
    	foreach ($data as $k=>$v) {
    		$data[$k] = str_replace($this->cyr, $this->lat, $v);
    	}
		$errors = new MessageBag();
		if($old = Input::old('errors')){
			$errors = $old;
		}
		$data['errors'] = $errors;

		$f = ["dow0_s","dow0_e","dow0_o","dow1_s","dow1_e","dow1_o","dow2_s","dow2_e","dow2_o","dow3_s","dow3_e","dow3_o","dow4_s","dow4_e","dow4_o","dow5_s","dow5_e","dow5_o","dow6_s","dow6_e","dow6_o"];
		$valRules = $this->getValidationRules($f);
		$valMsgs = $this->getValidationMessages($f);

		$validator = Validator::make($data, $valRules, $valMsgs);
		if($validator->passes()){
			try {
				DB::transaction(function() use ($data,$id) {
					DB::table('crm_partner_opening_hour')
						->where('partner_id','=',$id)
						->update(array(
							"is_deleted" => 1
						));
					for ($i=0; $i < 7; $i++) {
						DB::table('crm_partner_opening_hour')
							->insert(array(
								"partner_id" => $id,
								"day_of_week" => $i,
								"start_time" => $data['dow'.$i.'_s'] ? $data['dow'.$i.'_s'] : null,
								"end_time" => $data['dow'.$i.'_e'] ? $data['dow'.$i.'_e'] : null,
								"description" => $data['dow'.$i.'_o'] ? $data['dow'.$i.'_o'] : null
							));
					}
				});
			} catch (Exception $e) {
				$err[] = "Došlo je do greške".$e;
				$resp = ["status" => false, "errors" => $err];
				return $resp;
			}
			$resp = [
				"status" => true,
				"errors" => []
			];
			return $resp;
		}
		$resp = [
			"status"=> false,
			"errors"=>$validator->messages()->toArray()
		];
		return $resp;
	}

	public function savePartnerSiteText($id)
	{
		$data = Input::all();
    	foreach ($data as $k=>$v) {
    		$data[$k] = str_replace($this->cyr, $this->lat, $v);
    	}
		$errors = new MessageBag();
		if($old = Input::old('errors')){
			$errors = $old;
		}
		$data['errors'] = $errors;

		$f = ['short_text','long_text'];
		$valRules = $this->getValidationRules($f);
		$valMsgs = $this->getValidationMessages($f);

		$validator = Validator::make($data, $valRules, $valMsgs);
		if($validator->passes()){
			try {
				DB::transaction(function() use ($data,$id) {
					DB::statement(" INSERT INTO partner_about_gmx
										(partner_id,
										short_text,
										long_text)
									VALUES ({$id},
										'{$data['short_text']}',
										'{$data['long_text']}')
									ON DUPLICATE KEY UPDATE
										short_text = '{$data['short_text']}',
										long_text = '{$data['long_text']}'");
				});
			} catch (Exception $e) {
				$err[] = "Došlo je do greške".$e;
				$resp = ["status" => false, "errors" => $err];
				return $resp;
			}
			$resp = [
				"status" => true,
				"errors" => []
			];
			return $resp;
		}
		$resp = [
			"status"=> false,
			"errors"=>$validator->messages()->toArray()
		];
		return $resp;
	}

	public function savePartnerPaymentMethods($id)
	{
		return ["status"=>false, "errors"=>["Za promenu podataka na ovoj stranici, obratite se Gumamax-u"]];
	}

	public function savePartnerData($id)
	{
		return ["status"=>false, "errors"=>["Za promenu podataka na ovoj stranici, obratite se Gumamax-u"]];
	}


    public static function findBySubdomain($subdomain)
    {
        return self::where('subdomain', $subdomain)->first();
    }


	public static function apiDatatables(){

		$query = Partner::all();

		if ($query) {

			$d = Datatables::of($query);

			$d->addColumn('actions', function ($model)  {
				return view('admin.partners.dt-actions', compact('model'));
			});

			return $d->make(true);
		}

	}

	public function wheelAlignmentPrices()
	{
		$sqlWheelAlignment = "SELECT
					pd_vehicle_category.value_text AS vehicle_category,
					p.product_id,
					d.description,
					p.additional_description,
					pd_diameter.value_text AS diameter,
					pd_vrsta_felne.value_text AS vrsta_felne,
					cpj.product_id,
					cpj.price_with_tax as price
				FROM product p
				JOIN product_group pg ON pg.group_id=p.group_id
				JOIN description d ON d.description_id=p.description_id
				LEFT JOIN product_dimension pd_vehicle_category ON pd_vehicle_category.dimension_id=10 AND pd_vehicle_category.product_id=p.product_id
				LEFT JOIN product_dimension pd_diameter ON pd_diameter.dimension_id=13 AND pd_diameter.product_id=p.product_id
				LEFT JOIN product_dimension pd_vrsta_felne ON pd_vrsta_felne.dimension_id=24 AND pd_vrsta_felne.product_id=p.product_id
				LEFT JOIN partner_price_list cpj ON cpj.product_id=p.product_id AND cpj.partner_id=? AND cpj.deleted_at IS NULL
				WHERE p.group_id=439
				ORDER BY 2";

		return DB::connection('delmax_catalog')->select($sqlWheelAlignment,[$this->partner_id]);
	}


}
