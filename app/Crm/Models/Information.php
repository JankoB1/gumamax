<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:16
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $connection = 'CRM';

    protected $table = 'information';

    protected $primaryKey = 'id';

    protected $fillable = ['label', 'description', 'datatype', 'edit_control', 'lookup_json'];

}