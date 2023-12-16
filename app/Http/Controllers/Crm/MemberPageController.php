<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 21.10.2016
 * Time: 13:04
 */

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\DmxBaseController;
use Crm\Models\Member;
use Crm\Models\MemberPage;
use Illuminate\Http\Request;

class MemberPageController extends DmxBaseController
{

    public function create($memberId){

        $member = Member::find($memberId);

        $page = new MemberPage(['member_id'=>$memberId, 'name'=>'page', 'headline'=>$member->name]);

        $formUrl = url(route('crm.member-page.store'), [$page]);

        $formMethod = 'POST';

        return view('crm.projects.members.pages.edit', compact('page', 'formMethod', 'formUrl'));

    }

    public function store(Request $request){

        $page = MemberPage::create($request->all());

        if ($request->ajax()){

            return $this->respond($page);

        } else {

            return redirect()->back();

        }

    }

    public function edit($memberId){

        $page = MemberPage::where(['member_id'=>$memberId])->first();

        if($page){

            $formUrl = url(route('crm.member-page.update', [$page->id]));

            $formMethod = 'PUT';

            return view('crm.projects.members.pages.edit', compact('page', 'formMethod', 'formUrl'));
        } else {

            return $this->create($memberId);

        }

    }

    public function update(Request $request, $id){

        $page = MemberPage::find($id);

        if ($page){
            $page->update($request->all());
            if ($request->ajax()){
                return $this->respond($page);
            } else {
                return redirect()->back();
            }
        }

        abort(404);

    }

}