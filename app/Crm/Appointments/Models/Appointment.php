<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 9:33
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    protected $connection = 'CRM';
    protected $table = 'member_appointments';

}