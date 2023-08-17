<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 10.10.2016
 * Time: 14:40
 */

namespace App\Http\Controllers;


use App\Http\Controllers\DmxBaseController;
use Gumamax\Partners\Partner;
use Gumamax\Partners\PartnerAbout;
use Gumamax\Partners\SavePartnerAboutRequest;

class PartnerAboutController extends DmxBaseController
{

    public function index(){

    }

    public function create($id){

        $model  = new PartnerAbout(['partner_id'=>$id]);

        $formUrl = url(route('admin.partners.partner-about.store'));

        $formMethod='POST';

        return view('admin.partners.partials.information', compact('model', 'formUrl',  'formMethod'));

    }

    public function store(SavePartnerAboutRequest $request){

        $model = PartnerAbout::create($request->all());

        flash()->success('OK','');

        return redirect()->back();

    }

    public function edit($id){

        $model  = PartnerAbout::find($id);

        if ($model){

            $formUrl = url(route('admin.partners.partner-about.update', ['id'=>$id]));

            $formMethod='PUT';

            return view('admin.partners.partials.information', compact('model', 'formUrl',  'formMethod'));
        }

        return $this->create($id);

    }

    public function update(SavePartnerAboutRequest $request, $id){


    }
}