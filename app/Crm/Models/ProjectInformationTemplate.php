<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:17
 */

namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;
use Yajra\Datatables\Datatables;

class ProjectInformationTemplate extends Model
{
    protected $connection = 'CRM';

    protected $table = 'project_information_template';

    protected $primaryKey = 'id';

    protected $fillable = ['project_id', 'information_id', 'information_group_id', 'order_index', 'is_required'];

    public function information(){

        return $this->hasOne(Information::class, 'id', 'information_id');

    }

    public function group(){

        return $this->hasOne(InformationGroup::class, 'id', 'information_group_id');

    }

    public static function apiDatatablesProject($projectId){

        $query = ProjectInformationTemplate::leftJoin('information_group', 'information_group.id', '=', 'project_information_template.information_group_id')
            ->join('information', 'information.id', '=', 'project_information_template.information_id')
            ->select(
                'project_information_template.id',
                'project_information_template.information_group_id',
                'information_group.title as information_group',
                'information.id as information_id',
                'information.label',
                'information.description',
                'project_information_template.order_index')
            ->where('project_information_template.project_id', '=', $projectId)
            ->orderBy('project_information_template.order_index');

        $d = Datatables::of($query);

        $d->addColumn('actions', function($model){

            return view('crm.projects.information-templates.dt-actions', compact('model'));
        });

        return $d->make(true);

    }

    public static function apiDatatables(){

        $query = ProjectInformationTemplate::join('project', 'project.id','=', 'project_information_template.project_id')
            ->join('information', 'information.id', '=', 'project_information_template.information_id')
            ->leftJoin('information_group', 'information_group.id', '=', 'project_information_template.information_group_id')
            ->select(
                'project.name as project',
                'project_information_template.id',
                'project_information_template.information_group_id',
                'information_group.title as information_group',
                'information.id as information_id',
                'information.label',
                'information.description',
                'project_information_template.is_required',
                'project_information_template.order_index')
            ->orderBy('project_information_template.order_index');

        $d = Datatables::of($query);

        $d->addColumn('actions', function($model){

            return view('crm.projects.information-templates.dt-actions', compact('model'));
        });

        return $d->make(true);

    }

}