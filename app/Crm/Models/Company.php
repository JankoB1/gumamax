<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 16.11.2016
 * Time: 20:47
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'CRM';

    protected $table = 'company';

}