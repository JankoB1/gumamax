<?php namespace Delmax\Webapp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 10.8.2015
 * Time: 22:12
 */


class Country extends Model
{
    protected $connection = 'FW';

    protected $table = 'country';

    protected $primaryKey = 'country_id';

    public $incrementing = false;


    public static function allCached($columns=['*'])
    {
        return Cache::remember('countryAll', 120, function() use ($columns) {

            return Country::all($columns);
        });

    }

    public static function projectCountries($columns=['*'], $projectId)
    {
        return Cache::rememberForever('projectCountries-'.$projectId, function() use ($columns, $projectId) {

            return DB::connection('CRM')
                ->table('fw_001.country as country')
                ->join('project_country', 'project_country.country_id', '=', 'country.country_id')
                ->select($columns);
        });

    }

}