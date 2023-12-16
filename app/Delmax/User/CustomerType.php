<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 15.10.2016
 * Time: 0:25
 */

namespace Delmax\User;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{

    protected $connection = 'ApiDB';

    protected $table = 'customer_type';

    protected $primaryKey = 'customer_type_id';


}