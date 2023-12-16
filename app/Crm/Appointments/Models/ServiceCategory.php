<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 9:57
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $connection='CRM';

    protected $table='member_service_categories';
}