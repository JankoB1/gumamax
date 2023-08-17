<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 8.10.2016
 * Time: 13:05
 */

namespace App\Http\Controllers;


use App\Http\Controllers\DmxBaseController;
use Delmax\Partners\PartnerPriceList;
use Delmax\Partners\SavePriceRequest;
use Illuminate\Http\Request;

class PartnerPriceListController extends DmxBaseController
{

    /**
     * @var PartnerPriceList
     */
    private $partnerPriceList;

    public function __construct(PartnerPriceList $partnerPriceList){

        parent::__construct();

        $this->partnerPriceList = $partnerPriceList;
    }

    public function create($partnerId, $productId){

        $model = new PartnerPriceList();

        $model->partner_id = $partnerId;

        $model->product_id = $productId;

        $formUrl = '';

        $formMethod = '';

        return view('account.partner.pricelist.edit-price-modal', compact('model', 'formUrl', 'formMethod'));

    }

    public function store(SavePriceRequest $request){

        $model = $this->partnerPriceList->create($request->all());

        if ($request->ajax()){

            return $this->respond($model);

        }

        return null; //view ako nije poziv kroz ajax

    }

    public function edit(Request $request, $id){

        $model = $this->partnerPriceList->find($id);

        if ($model){
            if ($request->ajax()){
                return $this->respondNotFound();
            } else {
                return redirect()->back(404);
            }
        }

    }

    public function update(){

    }

    public function destroy(){

    }

    public function apiDatatablesAdminGmx($partnerId){

        $d = PartnerPriceList::apiDatatablesGmx($partnerId);

        return $d;
    }

    public function apiDatatablesCrm($memberId){
        $d = PartnerPriceList::apiDatatablesCrm($memberId);

        return $d;
    }

    public function apiEditablePost(SavePriceRequest $request){

        $pk = $request->get('pk');
        $id        = $pk['id'];
        $partnerId = $pk['partner_id'];
        $productId = $pk['product_id'];
        $priceWithTax = $request->get('value');

        if ($id){
            $model = PartnerPriceList::find($id);
        } else {
            $model = PartnerPriceList::create(['partner_id'=>$partnerId, 'product_id'=>$productId]);
        }

        if ($model){

            $model->price_with_tax = $priceWithTax;
            $model->save();
        }

        return $this->respond($model->toArray());

    }

}