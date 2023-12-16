<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.10.2016
 * Time: 12:27
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class PartnerUser extends Model
{
    protected $connection = 'FW';

    protected $table = 'partner_user';

    protected $primaryKey = 'id';


}