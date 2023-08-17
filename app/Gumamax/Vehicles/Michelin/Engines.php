<?php namespace app\Gumamax\Vehicles\Michelin;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 14:35
 */


class Engines extends Model{

    protected $connections='michelin';

    protected $table = 'michelin_engine';

    protected $primaryKey = 'engine_id';

}