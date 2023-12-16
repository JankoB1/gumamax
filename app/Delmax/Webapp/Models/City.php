<?php namespace Delmax\Webapp\Models;

use Delmax\Webapp\Traits\DmxDBTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.8.2015
 * Time: 4:55
 */
class City extends Model
{
    use DmxDBTrait;

    protected $connection = 'FW';

    protected $table = 'city';

    protected $primaryKey = 'city_id';

    protected $fillable = ['postal_code', 'city_name', 'country_id', 'latitude', 'longitude'];

    /**
     *  Returns data for jQuery datatables
     *  Column  "checkbox" must be created as first in row and must have class row_check
     *  Column "actions" must be created as last in row and must have class row_actions
     * @return mixed
     */
    public static function datatablesData(){

        $sqlFields = static::getDtSqlFields();

        return City::select($sqlFields);

    }

    public static function onlySelective($filter=''){

        if ($filter!==''){

            return DB::connection('FW')->table('city')
                ->select('city_id', 'city_name', 'postal_code')
                ->where('selective',1)
                ->where('city_name', 'like', $filter.'%')
                ->orderBy('city_name')
                ->get();

        }

        return Cache::remember('citiesSelective', 120, function(){

            return DB::connection('FW')->table('city')
                ->select('city_id','city_name', 'postal_code')
                ->where('selective',1)
                ->orderBy('city_name')
                ->get();
            });
    }

    public static function serbianCities(){

        return Cache::rememberForever('serbianCities', function(){

            return DB::connection('FW')->table('city')
                ->select('city_id', 'city_name', 'postal_code')
                ->where('selective',1)
                ->where('country_id','SRB')
                ->orderBy('city_name')
                ->get();
        });
    }

    public static function countryCities($countryId){

        return Cache::rememberForever($countryId.'-countryCities', function() use ($countryId){

            return DB::connection('FW')->table('city')
                ->select('city_id', 'name', 'postal_code')
                ->where('selective',1)
                ->where('country_id',$countryId)
                ->orderBy('name')
                ->get();
        });
    }

    public function country() {

        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
}