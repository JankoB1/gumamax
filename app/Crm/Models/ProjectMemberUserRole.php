<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 16:47
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectMemberUserRole extends Model
{

    protected $connection   =   'CRM';
    protected $table        =   'project_member_user_roles';

}