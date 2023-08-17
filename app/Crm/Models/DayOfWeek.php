<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.10.2016
 * Time: 14:42
 */

namespace Crm\Models;

use Illuminate\Database\Eloquent\Model;

class DayOfWeek extends Model
{

    protected $connection='FW';

    protected $table='day_of_week';

    protected $primaryKey = 'id';

}