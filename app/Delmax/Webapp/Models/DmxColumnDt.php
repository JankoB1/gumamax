<?php namespace Delmax\Webapp\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.8.2015
 * Time: 18:13
 */


class DmxColumnDt extends DmxModel
{
    use SoftDeletes;

    protected $table='dmx_column_dt';

    protected $fillable = [
        'dmx_id',
        'name',
        'data',
        'display_name_des_id',
        'is_visible',
        'column_search',
        'global_search',
        'orderable',
        'td_class_name'
    ];

}