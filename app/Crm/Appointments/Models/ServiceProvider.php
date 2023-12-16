<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 9:56
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{

    protected $connection='CRM';

    protected $table='member_service_providers';

}