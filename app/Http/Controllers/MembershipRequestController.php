<?php namespace App\Http\Controllers;
use App\Role;
use App\Models\User;
use Carbon\Carbon;
use Crm\Models\Project;
use Gumamax\Partners\MembershipRequest;
use Gumamax\Partners\RegisterPartnerRequest;
use Gumamax\Users\UserRepository;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use Zizaco\Entrust\EntrustRole;

class MembershipRequestController extends DmxBaseController
{

    public function index($status){

        if (in_array($status, ['opened', 'approved', 'rejected'])) {

            return view('admin.membership-request.index-'.$status, compact('status'));

        } else {

            //Nepoznat status
            abort(404);

        }

    }

    public function show($id, $status){

        $model = MembershipRequest::find($id);

        $formMethod ='GET';

        $formUrl = route('admin.membership-request.index-status', ['status'=>$status]);

        return view('admin.membership-request.show', compact('model','formMethod', 'formUrl'));

    }

    public function create(){

        $model = new MembershipRequest();

        $formMethod ='POST';

        $formUrl = route('partners.membership.store');

        return view('partner.membership.request', compact('model','formMethod', 'formUrl'));

    }

    public function edit(Request $request, $id){

        $model = MembershipRequest::find($id);

        if ($model){

            $model->processed_by_user_id=auth()->user()->user_id;

            $formMethod = 'PUT';

            $formUrl = route('admin.membership-request.update', [$id]);

            return view('admin.membership-request.edit', compact('model', 'formMethod', 'formUrl'));

        }

        abort(404);

    }

    public function update(Request $request, $id){

        $model = MembershipRequest::find($id);

        if ($model){

            $data = $request->only('approved_at', 'rejected_at');

            $model->update($data);

            $model->save();

            return redirect()->route('admin.membership-request.index-status', 'opened');

        }

        abort(404);

    }


    public function store(RegisterPartnerRequest $request){

        $membership = MembershipRequest::create($request->all());

        if ($membership) {
            return view('partner.membership.thanks');
        }

    }

    public function apiApprove($id, $partnerId){

        $userRepository = new UserRepository();

        $model = MembershipRequest::find($id);

        if ($model){

            $user = User::where('email', $model->email)->first();

            if (!$user){
                $user = User::register($model->email, $model->email, bcrypt($model->email),$model->first_name, $model->last_name,$model->phone,1,Carbon::now());
                $userRepository->save($user);
            }

            $user->attachRole(Role::gmxUser());
            $user->attachRole(9100);

            // Create Project membership
            // Create project role

            $model->approved_at = Carbon::now();

            $model->partner_id = $partnerId;

            $model->save();

            return 'OK';

        }
    }

    public function apiReject($id){

    }

    public function apiDatatables($status){

        switch ($status){
            case 'opened': {
                $query = MembershipRequest::whereNull('rejected_at')->whereNull('approved_at')->get();
                break;
            }
            case 'approved':{
                $query = MembershipRequest::whereNotNull('approved_at')->get();
                break;
            }
            case 'rejected':{
                $query = MembershipRequest::whereNotNull('rejected_at')->get();
                break;
            }
            default :{
                //Nepoznat status
                abort(404);
                break;
            }
        }

        if ($query) {

            $d = datatables()::of($query);

            $d->addColumn('actions', function ($model)  use ($status) {
                return view('admin.membership-request.actions', compact('model', 'status'));
            });

            return $d->make(true);
        }
    }

}