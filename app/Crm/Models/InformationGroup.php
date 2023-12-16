<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 16:22
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class InformationGroup extends Model
{
    protected $connection = 'CRM';

    protected $table = 'information_group';

    protected $primaryKey = 'id';

    protected $fillable = ['title'];



}