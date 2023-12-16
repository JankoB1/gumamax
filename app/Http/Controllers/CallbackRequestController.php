<?php
/**
 * Created by PhpStorm.
 * User: Bane
 * Date: 26.9.2016
 * Time: 16:24
 */

namespace App\Http\Controllers;

use Delmax\Models\CallbackRequest;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;


class CallbackRequestController extends DmxBaseController {


    public function index($status) {

        if (in_array($status, ['opened', 'closed'])) {

            return view('admin.callback-request.index-'.$status, compact('status'));

        } else {

            //Nepoznat status
            abort(404);

        }

    }

    public function create(Request $request) {

        $model = new CallbackRequest();

        $model->app_id = 2; //Posto dolazi sa gumamax-a

        $formUrl = url(route('home.callback-request.store'));

        $formMethod = 'POST';

        return view('home.callback-form', compact('model', 'formUrl', 'formMethod'));

    }

    public function store(Request $request) {

        $data = CallbackRequest::getData($request);

        $model = CallbackRequest::create($data);

        event('callbackRequest.created', compact('model'));
        
        if ($request->ajax()){

            return $model->getKey();

        }

        /**
         * Ovo je ako treba dase vrati na index stranicu recimo i da podigne poruku o gresci
         */
        /*
        flash()->success('Callback request', 'Zahtev je uspešno poslat');

        return redirect()->route('fw.index',['dmx_obj_id' => $dmx_obj_id]);
        */
    }

    public function edit(Request $request, $id){

        $model = CallbackRequest::find($id);

        if ($model){
            /**
             * Unapred setujemo aktivnog korisnika kao onog koji odgovara
             */
            $model->agent_id=auth()->user()->user_id;

            $model->called_at = date('Y-m-d H:i:s');

            $formMethod = 'PUT';

            $formUrl    = route('admin.callback-request.update', [$id]);

            return view('admin.callback-request.callback-reply-form-modal', compact('model', 'formMethod', 'formUrl'));

        }

        abort(404);

    }

    public function update(Request $request, $id){

        $model = CallbackRequest::find($id);

        if ($model){

            $data = $request->all();

            $data['closed'] = $data['closed']==0?null:$data['closed'];

            $model->update($data);

            $model->save();

            if ($request->ajax()){

                return $model->getKey();

            }

            return redirect()->route('admin.callback-request.index', ['status'=>'opened']);//Da bi se posle posta vratio na otvorene

        }

        abort(404);

    }

    public function apiDatatables($status){

        $query = CallbackRequest::apiDatatables($status);

        if ($query) {

            $d = datatables()::of($query);

            $d->addColumn('actions', function ($model)  use ($status) {
                return view('admin.callback-request.actions', compact('model', 'status'));
            });

            return $d->make(true);
        }

    }

    public function apiCount($status){

        $count = CallbackRequest::apiCount($status);

        return $this->respond(compact('count'));

    }



}