<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.10.2016
 * Time: 10:24
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Illuminate\Http\Request;

class InformationController extends DmxBaseController
{

    public function index(){

        return view('crm.information.index');

    }

    public function create(){

    }

    public function store(Request $request){

    }

    public function edit($id){

    }

    public function update(Request $request, $id){

    }

    public function destroy($id){

    }

}