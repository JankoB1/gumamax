<?php namespace Delmax\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 14.9.2015
 * Time: 11:52
 */


class UserPartner extends Model
{
    protected $connection   =   'ApiDB';

    protected $table        =   'user_partner';

}