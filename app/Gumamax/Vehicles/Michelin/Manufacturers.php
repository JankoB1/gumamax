<?php namespace app\Gumamax\Vehicles\Michelin;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 14:34
 */


class Manufacturers extends Model {

    protected $connection='michelin';

    protected $table = 'michelin_brand';

    protected $primaryKey = 'category_id';
}