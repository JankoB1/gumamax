<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.10.2016
 * Time: 0:25
 */

namespace App\Http\Controllers;


use Delmax\User\Activity\UserActivity;

class UserActivityController extends DmxBaseController
{

    public function apiDatatablesGmxUser($userId){

        return UserActivity::apiDatatablesGmx($userId, false);

    }

}