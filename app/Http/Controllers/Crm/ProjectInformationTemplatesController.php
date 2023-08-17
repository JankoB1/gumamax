<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.10.2016
 * Time: 10:27
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\ProjectInformationTemplate;

class ProjectInformationTemplatesController extends DmxBaseController
{

    public function index(){

        return view('crm.information-templates.index');

    }

    public function apiDatatablesProject($projectId){

        return ProjectInformationTemplate::apiDatatablesProject($projectId);

    }

    public function apiDatatables(){

        return ProjectInformationTemplate::apiDatatables();

    }

}