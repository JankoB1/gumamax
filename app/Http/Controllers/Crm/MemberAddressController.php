<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 23.10.2016
 * Time: 17:12
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Member;
use Delmax\Addresses\Address;
use Illuminate\Http\Request;

class MemberAddressController extends DmxBaseController
{

    public function index($memberId){

        $member = Member::find($memberId);

        return view('crm.projects.members.address.index', compact('member'));

    }


    public function update(Request $request, $memberId){
        $address_id = $request->get('id');

        $address = Address::find($address_id);

        if (!$address){

        }

    }

    public function apiDatatables($memberId){

        return Address::apiDatatablesMemberAddresses($memberId);

    }

}