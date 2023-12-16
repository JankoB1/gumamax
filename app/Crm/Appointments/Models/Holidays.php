<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 10:10
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{

    protected $connection='CRM';
    protected $table='member_holidays';

}