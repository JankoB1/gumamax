<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 22:57
 */

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\DmxBaseController;
use Crm\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends DmxBaseController
{

    public function index(){

        $projects = Project::apiItems();
        return view('crm.projects.dashboard', compact('projects'));

    }

    public function create(){

        $formMethod = 'POST';

        $formUrl = route('crm.projects.store');

        $project = new Project();

        $project->company_id=$this->crmCompanyId;

        return view('crm.projects.edit', compact('formMethod', 'formUrl', 'project'));

    }

    public function store(Request $request){

        $project = Project::create($request->all());

        if ($project){
            if ($request->ajax()){
                return $this->respond($project);
            } else {
                return $this->index();
            }
        }

    }

    public function edit($id){

        $project = Project::find($id);

        $formMethod='PUT';

        $formUrl=route('crm.projects.update', [$id]);

        return view('crm.projects.edit', compact('project', 'formMethod', 'formUrl'));

    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if ($project){
            $project->update($request->all()) ;

            if ($request->ajax()){
                return $this->respond($project);
            } else {
                return $this->index();
            }
        }

        abort(404);

    }

    public function destroy(Request $request, $id){

        $project = Project::find($id);

        if ($project){

            $project->delete();

            if ($request->ajax()){
                return $this->respond('OK');
            }else {
                return redirect()->back();
            }
        }

        abort(404);
    }

    public function apiDatatables(){

        return Project::apiDatatables();
    }

    public function apiItems(){


        $items = Project::apiItems();

        return compact('items');

    }
}