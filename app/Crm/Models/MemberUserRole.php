<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.10.2016
 * Time: 13:08
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;

class MemberUserRole extends Model
{
    protected $connection = 'CRM';

    protected $table = 'member_user_role';

    protected $primaryKey = 'id';

    protected $fillable = ['member_id', 'user_id', 'role_id'];

    public static function apiDatatables($memberId){

        //

    }


}