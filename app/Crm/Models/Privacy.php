<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 21.10.2016
 * Time: 13:35
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $connection = 'CRM';

    protected $table = 'privacy';

    protected $primaryKey = 'id';
}