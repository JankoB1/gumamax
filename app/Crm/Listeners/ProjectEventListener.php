<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.10.2016
 * Time: 9:51
 */

namespace Crm\Listeners;


use Crm\Models\Member;
use Crm\Models\Project;

class ProjectEventListener
{
    public function __construct(){

    }

    public function onProjectCreate(Project $project){

    }

    public function onMemberAdded(Member $member){

        $member->project->updateMembersCounter();

    }

    public function onMemberRemoved(Member $member){

        $member->project->updateMembersCounter();

    }

    public function onMemberInformationUpdated(){

    }
}