<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.10.2016
 * Time: 6:43
 */

namespace Gumamax\Partners;


use Illuminate\Database\Eloquent\Model;

class PartnerAbout extends Model
{

    protected $connection='CRM';

    protected $table = 'partner_resource_gmx';

    protected $primaryKey = 'id';

    protected $fillable =['partner_id'];

}