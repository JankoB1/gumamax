<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.10.2016
 * Time: 7:19
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\MemberPaymentMethod;
use Illuminate\Http\Request;

class MemberPaymentMethodController extends DmxBaseController
{

    public function index(){

    }

    public function edit($memberId){



    }

    public function update(Request $request, $memberId){

        $data = $request->get('payment_method');

        foreach ($data as $key=>$value){
            $mpm =  MemberPaymentMethod::where(['member_id'=>$memberId, 'payment_method_id'=>$key])->first();
            if (($value==0)&&($mpm)){
                    $mpm->delete();
            } else if (($value==1)&&(!$mpm)) {
                    MemberPaymentMethod::create(['member_id' => $memberId, 'payment_method_id' => $key]);
            }
        }
        if ($request->ajax()){
            return $this->respond($data);
        } else {
            return redirect()->back();
        }



    }

}