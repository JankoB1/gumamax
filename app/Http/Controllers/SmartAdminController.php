<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 27.9.2016
 * Time: 22:37
 */

namespace App\Http\Controllers;


class SmartAdminController extends DmxBaseController
{

    public function index(){

        return view('sa-template.admin.master');


    }

}