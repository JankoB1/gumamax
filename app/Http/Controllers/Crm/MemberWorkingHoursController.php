<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 23.10.2016
 * Time: 14:20
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\MemberWorkingHour;
use Illuminate\Http\Request;

class MemberWorkingHoursController extends DmxBaseController
{

    public function edit($memberId){

        return view('crm.projects.members.working-hours', compact('memberId'));

    }

    public function apiEditablePost(Request $request){

        $pk = $request->get('pk');
        $id                  = $pk['id'];
        $memberId           = $pk['member_id'];
        $dayOfWeekId         = $pk['day_of_week_id'];
        $value               = $request->get('value');

        if ($id){
            $model = MemberWorkingHour::find($id);
        } else {
            $model =  MemberWorkingHour::create(['member_id'=>$memberId, 'day_of_week_id'=>$dayOfWeekId]);
        }

        if ($model){

            $model->start_time = $value['start_time'];
            $model->end_time = $value['end_time'];
            $model->info = $value['info'];
            $model->save();
        }

        return $this->respond($model->toArray());

    }

    public function apiDatatables($memberId){

        return MemberWorkingHour::datatablesApi($memberId);

    }

}