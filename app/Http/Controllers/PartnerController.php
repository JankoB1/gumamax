<?php

namespace App\Http\Controllers;

use Crm\Models\Member;
use Crm\Models\Partner;
use Crm\Services\PartnerLocator\PartnerLocator;
use Delmax\Cart\Services\DelmaxCartService;
use Delmax\Webapp\Models\City;
use Gumamax\Partners\CartInstallationCost;
use Gumamax\Partners\PartnerPaymentMethod;
use Gumamax\Partners\SavePartnerRequest;
use Illuminate\Http\Request;

class PartnerController extends Controller
{

    public $cartService;
    /**
     * @var PartnerLocator
     */
    private $partnerLocator;

    public function __construct(DelmaxCartService $cartService, PartnerLocator $partnerLocator)
    {
        parent::__construct();

        $this->cartService = $cartService;

        $this->partnerLocator = $partnerLocator;

    }

    public function showPartners() {
        return view('partners');
    }

    public function showSinglePartner() {
        return view('single-partner');
    }

    public function edit(Request $request, $id){

        $partner = Partner::find($id);

        $formUrl = url(route('crm.partners.update', ['id'=>$id]));

        $formMethod = 'PUT';

        if ($partner) {

            if ($request->ajax()){
                $modal = true;
                return view('crm.partners.edit', compact('partner', 'formUrl', 'formMethod', 'modal'));
            } else {
                $modal = false;
                return view('crm.partners.edit', compact('partner', 'formUrl', 'formMethod', 'modal'));
            }

        }

        abort(404);
    }

    public function update(SavePartnerRequest $request, $id){

        $model = Partner::find($id);

        if ($model){

            $model->erp_company_id  = $request->get('erp_company_id');

            $model->erp_partner_id  = $request->get('erp_partner_id');

            $model->description     = $request->get('description');
            $model->description2    = $request->get('description2');
            $model->address         = $request->get('address');

            $model->city_id         = $request->get('city_id');
            $city = City::find($request->get('city_id'));
            $model->postal_code     = $city->postal_code;
            $model->city_name       = $city->city_name;

            $model->country_id      = $city->country->country_id;
            $model->country_name = $city->country->name;
            $model->country_iso_alpha_2 = $city->country->iso_alpha_2;
            $model->country_iso_alpha_3 = $city->country->iso_alpha_3;

            $model->phone   = $request->get('phone');
            $model->fax     = $request->get('fax');
            $model->email   = $request->get('email');
            $model->web_address = $request->get('web_address');
            $model->tax_identification_number = $request->get('tax_identification_number');
            $model->latitude    = $request->get('latitude');
            $model->longitude   = $request->get('longitude');

            $model->save();

            if ($request->ajax()){
                return $this->respond($model);
            }else{
                flash()->success('OK','');
                return redirect()->back();
            }
        }

        if ($request->ajax()){
            return $this->respondNotFound();
        }else{
            abort(404);
        }

    }

    public function apiDatatables(){

        $d = Partner::apiDatatables();

        return $d;
    }


    public function show2($id)
    {
        $partner = Partner::find($id);
        if ($partner){
            return view('partner.show2', compact('partner'));
        }
        abort(404);
    }

    public function show($id)
    {
        $data = $this->getPartnerData($id);
        return view('partner.show', $data);
    }


    public function searchNearestApi(Request $request)
    {
        $data = $this->partnerLocator->nearestByRequest($request);

        return $data;
    }


    public function availablePaymentMethod($partner_id)
    {
        return PartnerPaymentMethod::getListByPartnerId($partner_id);
    }

    public function getPartnerBySubdomain($subdomain)
    {
        if (trim($subdomain) == '') {

            session()->forget('subdomain');

            return redirect(config('app.url'));

        } else {

            $partner = Partner::findBySubdomain($subdomain);

            if ($partner) {
                $domainObject = [
                    "name"          => $subdomain,
                    "partner_id"    => $partner->partner_id,
                    "partner_name"  => $partner->name
                ];

                subdomain($domainObject);

                return $this->showPartner($partner->partner_id);

            } else {
                // TODO: (Marcha) da li redirekt na glavnu stranu ili obavestiti korisnika da je pogresna adresa?
                // abort(404);
                return redirect(config('app.url'));
            }
        }
    }

    public function showPartnerPopup($id){
        return view('partner.show-popup', $this->getPartnerData($id));
    }

    public function login()
    {
        return view('partner.membership.login');
    }

    public function apiShippingPartnerMoreInfo(Request $request){

        $items = $request->get('items');

        $memberId = $request->get('member_id');

        $member  = Member::find($memberId);

        if ($member){
            $data['install_price']  =  CartInstallationCost::calculate($items, $memberId);
            $data['payment_methods'] = $member->paymenthMethods;
        } else {
            $data['install_price']  =  null;
            $data['payment_methods'] = null;
        }

        return $data;
    }

}
