<?php namespace Gumamax\Vehicles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserVehicle extends Model {

    use SoftDeletes;

    protected $connection='ApiDB';
    protected $table='user_vehicle';
    protected $primaryKey='user_vehicle_id';
    protected $fillable = [
        'user_id',
        'year',
        'mfa_id',
        'mod_id',
        'typ_id',
        'name',
        'vin',
        'engine_code'

    ];

    public static function makeByValues($year, $mfa_id, $mod_id, $typ_id, $name, $vin, $engine_code){

        return new static (compact('year', 'mfa_id', 'mod_id', 'typ_id', 'name', 'vin', 'engine_code'));

    }

    public static function make(Array $data){

        return new static ($data);

    }
 }