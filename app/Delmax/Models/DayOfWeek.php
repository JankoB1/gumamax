<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.10.2016
 * Time: 18:33
 */

namespace Delmax\Models;


use Illuminate\Database\Eloquent\Model;

class DayOfWeek extends Model
{

    protected $connection='delmax_gumamax';

    protected $table='day_of_week';

    protected $primaryKey = 'day_of_week_id';

}