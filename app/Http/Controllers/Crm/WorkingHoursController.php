<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.10.2016
 * Time: 13:25
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\WorkingHours;
use Illuminate\Http\Request;

class WorkingHoursController extends DmxBaseController
{

    public function edit($partnerId){

        return view('crm.partners.partials.working-hours', compact('partnerId'));

    }

    public function apiEditablePost(Request $request){

        $pk = $request->get('pk');
        $id                  = $pk['id'];
        $partnerId           = $pk['partner_id'];
        $dayOfWeekId         = $pk['day_of_week_id'];
        $value               = $request->get('value');

        if ($id){
            $model = WorkingHours::find($id);
        } else {
            $model =  WorkingHours::create(['partner_id'=>$partnerId, 'day_of_week_id'=>$dayOfWeekId]);
        }

        if ($model){

            $model->start_time = $value['start_time'];
            $model->end_time = $value['end_time'];
            $model->info = $value['info'];
            $model->save();
        }

        return $this->respond($model->toArray());

    }

    public function apiDatatables($partnerId){

        return WorkingHours::datatablesApi($partnerId);

    }

}