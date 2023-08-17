<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 10:48
 */

namespace App\Http\Controllers\Appointments\Frontend;


use App\Http\Controllers\DmxBaseController;
use Crm\Appointments\Models\Service;
use Crm\Models\Member;

class BookingController extends DmxBaseController
{

    public function apiServices($memberId){

        $data = Member::apiServices($memberId);

        return $this->respondWithData();

    }

}