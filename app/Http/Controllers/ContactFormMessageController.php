<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 3.10.2016
 * Time: 3:29
 */

namespace App\Http\Controllers;


use Delmax\Mailers\UserMailer;
use Delmax\Models\ContactFormMessage;
use Delmax\Models\SendContactFormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Yajra\Datatables\Facades\Datatables;

class ContactFormMessageController extends DmxBaseController
{

    /**
     * @var UserMailer
     */
    private $mailer;

    public function __construct(UserMailer $mailer){
        parent::__construct();

        $this->mailer = $mailer;
    }

    public function create() {

        $model = new ContactFormMessage();

        $model->app_id = 2; //Posto dolazi sa gumamax-a

        $formUrl = url(route('home.contact-form-message.store'));

        $formMethod = 'POST';

        return view('home.contact-form-modal', compact('model', 'formUrl', 'formMethod'));

    }


    public function store(SendContactFormRequest $request){

        $data = $request->all();
        $data['from_ip'] = getIpAddress();
        $data['app_id'] = 2;

        if (auth()->check()){
            $data['user_id'] = auth()->user()->user_id;
        }

        $data['time'] = date('Y-m-d H:i:s');
        $data['text'] = $data['message'];
        unset($data['message']);

        $model = ContactFormMessage::create($data);

        if ($request->ajax()){
            $this->mailer->sendContactFormMail($data);
            return $model->getKey();
        }
    }

    public function index($status){

        if (in_array($status, ['opened', 'answered'])) {

            return view('admin.contact-form-message.index-'.$status, compact('status'));

        } else {

            //Nepoznat status
            abort(404);

        }

    }

    public function show($id){

        $model = ContactFormMessage::find($id);

        if ($model){

            return view('admin.contact-form-message.show', compact('model'));

        }

        abort(404);
    }

    public function edit($id){

        $model = ContactFormMessage::find($id);

        if ($model) {

            $formMethod = 'PUT';

            $formUrl    = route('admin.contact-form-message.update', [$id]);

            return view('admin.contact-form-message.contact-reply-form-modal', compact('model', 'formUrl', 'formMethod'));

        }

        abort(404);

    }



    public function update(Request $request, $id){

        $model = ContactFormMessage::find($id);

        if ($model){

            $data = $request->only('answer');

            $model->update($data);

            $model->answered_at = date('Y-m-d H:i:s');

            $model->save();

            $this->mailer->sendReplyToContact($model);

            return redirect()->route('admin.contact-form-message.index-status', 'opened');

        }

        abort(404);
    }

    public function apiDatatables($status){

        switch ($status){
            case 'opened': {
                $query = ContactFormMessage::whereNull('answered_at')->get();
                break;
            }
            case 'answered':{
                $query = ContactFormMessage::whereNotNull('answered_at')->get();
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
                return view('admin.contact-form-message.actions', compact('model', 'status'));
            });

            return $d->make(true);
        }
    }


    public function apiCount($status){

        $count = ContactFormMessage::apiCount($status);

        return $this->respond(compact('count'));
    }


}