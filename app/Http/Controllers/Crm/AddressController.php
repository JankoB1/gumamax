<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 22.10.2016
 * Time: 0:17
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Delmax\Addresses\Address;
use Illuminate\Http\Request;

class AddressController extends DmxBaseController
{

    public function index(){



    }

    public function create($memberId){

        $formMethod='POST';

        $formUrl=url(route('crm.member.address.store'));

        $address = new Address(['addressable_id'=>$memberId, 'addressable_type'=>'Crm\\Models\\Member', 'address_type_id'=>1]);

        $modal = false;

        return view('crm.address.edit', compact('address', 'formMethod', 'formUrl', 'modal'));

    }

    public function store(Request $request){

        return true;

    }

    public function edit($id){

        $address = Address::find($id);

        if ($address){
            $formMethod= 'PUT';

            $formUrl = url(route('crm.address.update', ['id'=>$address->id]));

            $modal = false;

            return view('crm.address.edit', compact('address', 'formMethod', 'formUrl', 'modal'));
        }

        abort(404);


    }

    public function update(){

    }

    public function destroy(){

    }

}