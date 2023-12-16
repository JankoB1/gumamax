<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 23:04
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use App\Models\User;
use Carbon\Carbon;
use Crm\Models\Member;
use Crm\Models\Partner;
use Crm\Models\Project;
use Crm\Requests\CreateMemberRequest;
use Illuminate\Http\Request;


class MemberController extends DmxBaseController
{    
    /**
     * @var Project
     */
    private $project;

    public function __construct(Project $project){
        parent::__construct();
        $this->project = $project;
    }

    public function projectIndex($projectId)
    {
        $project = Project::find($projectId);

        if($project){
            return view('crm.projects.members.index', compact('project'));
        }
        abort(404);
    }

    public function index()
    {

    }

    public function create(Request $request){

        $projectId = $request->get('projectId');

        $memberTypePhp = null;

        $memberType = $request->get('memberType');

        if ($memberType=='partner'){
            $memberTypePhp = 'Crm\\Models\\Partner';
        }

        $member = new Member(['project_id'=>$projectId, 'membership_type'=>$memberTypePhp]);

        $formUrl = route('crm.member.store');

        $formMethod = 'POST';

        return view('crm.projects.members.form', compact('member', 'formUrl', 'formMethod'));

    }

    public function store(CreateMemberRequest $request){

        $member = Member::create($request->all());

        event('member.added', [$member]);

        if ($request->ajax()){

            $this->respond($member);

        }

    }

    public function show(Request $request, $id){

        $member = Member::find($id);

        if ($member){

            return view('member.show', compact('member'));
        }

        abort(404);
    }

    public function edit(Request $request, $id){

        $tab = $request->get('tab', 'membership');
        $tabUsers = $request->get('tabUsers', 'crm_member_admin');

        $member = Member::find($id);

        if ($member){

            $formMethod = 'PUT';

            $formUrl = route('crm.member.update',['id'=>$id, 'tab'=>$tab, 'tabUsers'=>$tabUsers]);

            return view('crm.projects.members.edit-tabbed', compact('member', 'formMethod', 'formUrl', 'tab', 'tabUsers'));
        }

        abort(404);
    }

    public function update(Request $request, $id){

        $member = Member::find($id);

        if ($member){

            $member->update($request->all());

            if($request->ajax()){

                return $this->respond($member);

            } else {

                return redirect()->back();
            }
        }

        abort(404);
    }

    public function destroy(Request $request, $id){

        $member = Member::find($id);
        $member->delete();

        event('member.removed', compact('member'));

        if ($request->ajax()){
            return $this->respond('ok');
        }
    }

    public function apiDatatables($projectId){

        return Member::apiDatatables($projectId);

    }

    public function apiDtPartners($projectId){

        return Member::apiDtPartners($projectId);

    }

    public function apiAddMemberByErpPartnerId(Request $request){

        $project_id = $request->get('project_id');
        $erp_partner_id = $request->get('erp_partner_id');

        $partner = Partner::where('erp_partner_id', $erp_partner_id)->first();

        if ($partner) {
            $member = Member::create([
                'project_id'=>$project_id,
                'membership_type'=>'Crm\\Models\\Partner',
                'membership_id'=>$partner->id,
                'approved_at'=>Carbon::now(),
                'erp_partner_id'=>$erp_partner_id,
                'erp_company_id'=>$partner->erp_company_id,
                'name'=>$partner->description,
                'department'=>$partner->description2,
                'city_id'=>$partner->city_id,
                'city_name'=>$partner->city_name,
                'country_id'=>$partner->country_id,
                'country_name'=>$partner->country_name,
                'address'=>$partner->address,
                'postal_code'=>$partner->postal_code,
                'longitude'=>$partner->longitude,
                'latitude'=>$partner->latitude,
            ]);
            return $this->respond(['status'=>'ok', 'info'=>$member->id]);
        } else {
            return $this->respond(['status'=>'error', 'info'=>'Erp partner not found!']);
        }
    }
}