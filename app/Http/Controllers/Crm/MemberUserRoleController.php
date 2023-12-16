<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.11.2016
 * Time: 19:01
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\MemberUserRole;
use Illuminate\Http\Request;

class MemberUserRoleController extends DmxBaseController
{

    public function create(){

    }

    public function store(Request $request){

        $memberUserRole = MemberUserRole::create($request->all());

        if ($memberUserRole){

            if ($request->ajax()){
                return $this->respond($memberUserRole);
            } else {
                return redirect()->back();
            }

        }

    }

    public function destroy(Request $request, $id){

        $memberUserRole = MemberUserRole::find($id);

        if($memberUserRole){

            $memberUserRole->delete();

            if($request->ajax()){

                return $this->respond('OK');

            } else {

                return redirect()->back();

            }

        }

        abort(404);
    }

}