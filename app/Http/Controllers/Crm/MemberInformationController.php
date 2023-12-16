<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 16:27
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Member;
use Crm\Models\MemberInformation;
use Crm\Requests\SaveMemberInformationRequest;


class MemberInformationController extends  DmxBaseController
{

    public function index() {

        return view();
    }

    public function edit($id) {

        $member = Member::find($id);
        return view('crm.projects.members.information', compact('member'));
    }

    public function apiEditable(SaveMemberInformationRequest $request) {

        $data = $request->all();

        if ($data['id']) {
            $model = MemberInformation::find($data['id']);
        } else {
            $model = MemberInformation::create($data);
        }

        if ($model) {
            $model->update($data);
            $model->save();
        }

        return $this->respond($model->toArray());
    }

    public function apiDatatables($id){
        
        $member = Member::find($id);

        return MemberInformation::apiDatatables($member);
    }

    public function apiDtFrontend($id){

        $member = Member::find($id);

        return MemberInformation::apiDtFrontend($member);
    }

}