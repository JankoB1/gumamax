<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.10.2016
 * Time: 10:44
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Member;
use Crm\Models\MemberPriceList;
use Illuminate\Http\Request;

class MemberPriceListController extends DmxBaseController
{

    public function edit($memberId){

        $member = Member::find($memberId);

        return view('crm.projects.members.price-list', compact('member'));

    }

    public function apiEditablePost(Request $request){

        $pk = $request->get('pk');
        $id        = $pk['id'];
        $memberId  = $pk['member_id'];
        $productId = $pk['product_id'];
        $priceWithTax = $request->get('value');

        if ($id){
            $model = MemberPriceList::find($id);
        } else {
            $model = MemberPriceList::create(['member_id'=>$memberId, 'product_id'=>$productId]);
        }

        if ($model){

            $model->price_with_tax = $priceWithTax;

            $model->save();
        }

        return $this->respond($model->toArray());

    }

    public function apiDatatables($memberId){

        return MemberPriceList::apiDatatables($memberId);

    }

    public function apiDtTyresServices($memberId, $vehicleCategory, $diameter){

        return MemberPriceList::apiDtTyresServices($memberId, $vehicleCategory, $diameter);

    }

    public function apiDtTyresOtherServices($memberId){

        return MemberPriceList::apiDtTyresOtherServices($memberId);

    }

    public function apiDtWheelAlignmentServices($memberId, $vehicleCategory, $diameter){

        return MemberPriceList::apiDtWheelAlignmentServices($memberId, $vehicleCategory, $diameter);

    }

    public function apiDtWheelAlignmentOtherServices($memberId){

        return MemberPriceList::apiDtWheelAlignmentOtherServices($memberId);

    }

}