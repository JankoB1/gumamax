<?php namespace App\Delmax\Products;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 7.4.2015
 * Time: 19:33
 */


class Descriptions extends Model{

    protected $connections='delmax_catalog';

    protected $table='description';

    protected $primaryKey = 'description_id';

    public function dimensionsTemplate()
    {
        return $this->belongsToMany('App\Gumamax\Dimension', '');
    }
}