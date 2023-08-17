<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 10:00
 */

namespace Crm\Appointments\Models;


use Illuminate\Database\Eloquent\Model;

class Breaks extends Model
{
    protected $connection='CRM';
    protected $table = 'member_user_breaks';

}