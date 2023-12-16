<?php namespace App\Gumamax;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Exception;
use Response;

class City extends Model {

	protected $table = 'city';

	protected $primaryKey = 'city_id';

	public $incrementing = false;

	public $timestamps = false;

	protected $fillable = ['city_id'];

    private $valRules = [
         "city_id"=>"required|integer",
         "city_name"=>"required|min:2|max:255",
         "country_id"=>"required",
         "postal_code"=>"required|integer",
         "latitude"=>"numeric",
         "longitude"=>"numeric"
        ];

    private $valMsgs = array(
         "city_id.required"=>"Obavezno unesite ID mesta",
         "city_id.integer"=>"ID mesta nije u ispravan broj",
         "city_name.required"=>"Obavezno unesite naziv mesta",
         "city_name.min"=>"Naziv mesta može imati najmanje :min karaktera",
         "city_name.max"=>"Naziv mesta može imati najviše :max karaktera",
         "country_id.required"=>"Obavezno unesite troslovni kod države",
         "postal_code.required"=>"Obavezno unesite poštanski broj",
         "postal_code.integer"=>"Poštanski broj nije u ispravnom formatu",
         "latitude.numeric"=>"Latituda nije u ispravnom formatu",
         "longitude.numeric"=>"Longituda nije u ispravnom formatu"
        );

	public function getCities($fmt='', $filter='')
	{
		$w =  ($filter=='') ? '' : " AND (c.city_name LIKE '%{$filter}%' OR c.postal_code LIKE '%{$filter}%') ";
		$sql = "SELECT
					c.city_id,
					CONCAT_WS(', ', c.city_name,c.postal_code) AS label,
					CONCAT_WS(':', c.city_id, c.country_id, c.postal_code, c.latitude, c.longitude) AS VALUE,
					c.city_name, c.postal_code, c.latitude, c.longitude,
					COALESCE(sc.free_shipping, ssf.value_int, 48) AS free_shipment,
					COALESCE(sc.courier_shipping, ssc.value_int, 48) AS courier_shipment
				FROM city c
				JOIN site_settings ssf ON ssf.description = 'default_free_shipment'
				JOIN site_settings ssc ON ssc.description = 'default_courier_shipment'
				LEFT JOIN shipment_city sc ON c.city_id=sc.city_id
				WHERE
					c.city_id != 1 {$w}
				ORDER BY c.city_name";

		switch ($fmt) {
			case 'json':
				return Response::json(DB::select($sql));
				break;
			case 'xml':
				return 'TODO: xml';
				break;
			case 'datatables':
                // TODO: Datatables for L5: "yajra/laravel-datatables-oracle": "~4.0"
                return 'TODO: add bliim/datatables + return datatables json';
				break;

			default:
				return DB::select($sql);
				break;
		}
	}

    public static function getGeoCode($city_id){
        return DB::table('city')->select('city_id', 'latitude', 'longitude')->where('city_id',$city_id)->get();
    }

    public function getShipmentCity($cid=0)
    {
    	$fs = Config::get("gumamax.default_free_shipping");

        $cs = Config::get("gumamax.default_courier_shipping");

    	$r = DB::select("
    	    SELECT
    	        COALESCE(sc.free_shipping,{$fs}) AS free_shipping,
    	        COALESCE(sc.courier_shipping,{$cs}) AS courier_shipping
            FROM city c
                 LEFT JOIN shipment_city sc ON sc.city_id=c.city_id
            WHERE c.city_id={$cid}");

        if (count($r)==0){
            $r = DB::select(" SELECT {$fs} as free_shipping, {$cs} as courier_shipping ");
        }
        return $r;
    }

    public function saveCity($data)
    {
        $errors = new MessageBag;
        if($old = Input::old('errors')){
        	$errors = $old;
        }
        $data['errors'] = $errors;

        $validator = Validator::make( $data, $this->valRules, $this->valMsgs );
        if($validator->passes())
        {
			try {
				$city = City::firstOrCreate(array( 'city_id'=>$data['city_id']  ));
				$city->city_name = $data['city_name'];
				$city->country_id = strtoupper($data['country_id']);
				$city->postal_code = $data['postal_code'];
				$city->latitude = $data['latitude'];
				$city->longitude = $data['longitude'];
				$city->save();
			} catch (Exception $e) {
				$err[] = "Došlo je do greške prilikom snimanja podataka. ".$e;
			}
    		$err = [];
        }
        $data["errors"] = $validator->errors();
        if(isset($err)){
        	$data['errors'][] = $err;
        }
        return $data['errors'];
    }


}
