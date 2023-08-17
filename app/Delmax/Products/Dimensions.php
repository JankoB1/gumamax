<?php namespace App\Delmax\Products;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 21:10
 */


use Illuminate\Database\Eloquent\Model;

class Dimensions extends Model{

    protected $connections='delmax_catalog';

    protected $table = 'dimension';

    protected $primaryKey = 'dimension_id';


}