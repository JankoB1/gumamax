<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 10:18
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $connection='CRM';

    protected $table = 'member_vacations';

}