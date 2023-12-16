<?php

namespace App\Http\Controllers;

use Delmax\Addresses\Address;
use Delmax\Addresses\AddressType;
use Delmax\Addresses\SaveAddressRequest;
use Delmax\Cart\Services\DelmaxCartService;
use Delmax\Models\ShippingOption;
use Delmax\Shipping\ShippingCalculator;
use Delmax\User\Guest;
use App\Http\Requests;
use Delmax\Partners\Partner;
use Delmax\Webapp\Models\City;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    private $cartService;
    /**
     * @param DelmaxCartService $cartService
     */
    public function __construct(DelmaxCartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        if (subdomain()->inUse()){
            return redirect('/');
        }
        /*
                $cart = $this->cartService->getOpened();

                if ($cart) {
                    $cart->load('items', 'shippingToAddress.city', 'shippingToPartner', 'installationCosts');
                }

        $formParams = $this->getAddressFormParams($cart);

        $newAddressModel = $this->getNewAddressModel();

        $shippingAddressIsSelected = isset($cart)&&(!is_null($cart->shipping_to_address_id));
        */
        return view('shipping.js-index');
    }

    private function getAddressFormParams($cart)
    {
        $formMethod = 'POST';
        $formAction = url('shipping/location/address/');

        if (isset($cart) && auth()->guest() && ($cart->shipping_option_id==ShippingOption::CUSTOM_ADDRESS)&&($cart->shippingToAddress)){
            $formMethod = 'PUT';
            $formAction = url('shipping/location/address/'.$cart->shippingToAddress->id);
        }

       return compact('formMethod', 'formAction');
    }

    private function getNewAddressModel()
    {
        $addressModel = null;

        if (auth()->check()){
            $visitor = auth()->user();
            $addressable_type = 'App\Models\User';
            $addressable_id = $visitor->user_id;

        } else {
            $visitor = Guest::getActiveGuest();
            $addressable_type = 'Delmax\User\Guest';
            $addressable_id = $visitor->id;
        }

        $address_type_id = AddressType::BILLING_AND_GOODS_DELIVERY;

        return Address::make(['addressable_id'=>$addressable_id, 'addressable_type'=>$addressable_type, 'address_type_id'=>$address_type_id]);
    }

    private function getAddressModel($cart)
    {
        $addressModel = null;
        if ($cart){
            if ($cart->shipping_option_id==ShippingOption::CUSTOM_ADDRESS){

            }
        }
    }

    public function update(SaveAddressRequest $request, $id)
    {
        $model = Address::find($id);

        if (!$model) {

            abort(404);

        }

        $model->fill($request->all());

        $model->save();

        return redirect(url('shipping/location?tab=address'));
    }

    public function store(SaveAddressRequest $request)
    {
        $model = Address::make($request->all());

        if (auth()->guest()){
            $visitor = Guest::getActiveGuest();
        } else {
            $visitor = auth()->user();
        }

        $visitor->addAddress($model);

        $this->cartService->setShippingToAddress($model->id);

        return redirect('/shipping/location?tab=address');
    }

    public function apiSetCosts(Request $request){

        $items = $request->get('items');

        $shippingMethodId = $request->get('shipping_method_id');

        $calculator = new ShippingCalculator($shippingMethodId);

        $calculator->setShippingCost($items);

        return $items;
    }

    public function apiGetCosts(Request $request){

        $items = $request->get('items');

        $shippingMethodId = $request->get('shipping_method_id');

        $data = (new ShippingCalculator($shippingMethodId))->getShippingCost($items);

        return $data;
    }

    public function apiSubdomainInfo(Request $request) {
        
        $subdomain = $request->get('subdomain');
        $partner = Partner::findBySubdomain($subdomain);
        if ($partner){
            $city = City::find($partner->city_id);
            $data = [
                'partner_id'            => $partner->partner_id,
                'shipping_recipient'    => $partner->name,
                'shipping_address'      => $partner->address,
                'shipping_address2'     => null,
                'shipping_city'         => $city->city_name,
                'shipping_postal_code'  => $city->postal_code,
                'shipping_country_code' => $city->country_id,
                'shipping_phone'        => $partner->phone,
                'shipping_email'        => $partner->email
            ];

            return json_encode($data);

        }

    }
}
