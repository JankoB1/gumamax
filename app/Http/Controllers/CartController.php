<?php

namespace App\Http\Controllers;

use Crm\Models\MemberPaymentMethod;
use Delmax\Cart\Requests\CartAddItemRequest;
use Delmax\Cart\Requests\CartChangeQtyRequest;
use Delmax\Cart\Requests\CartDeleteItemRequest;
use Delmax\Cart\Requests\CartEmptyRequest;
use Delmax\Cart\Services\DelmaxCartService;
use Delmax\Models\ShippingMethod;
use Delmax\Shipping\ShippingCalculator;
use Exception;
use Gumamax\Partners\CartInstallationCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CartController extends DmxBaseController
{
    /**
     * @var DelmaxCartService
     */
    private $cartService;

    /**
     * @param DelmaxCartService $cartService
     */
    public function __construct(DelmaxCartService $cartService)
    {
        parent::__construct();

        $this->cartService = $cartService;
    }

    /**
     * Show current cart
     * @return mixed
     */

    public function show()
    {
        return view('cart.show');

    }

    public function apiGetOpened()
    {
        $cart = $this->cartService->getOpened();

        if ($cart) {
            $cart->load('items', 'shippingToAddress.city', 'shippingToPartner', 'installationCosts');
        }

        return $this->respondWithData($cart);

    }

    public function addItem(CartAddItemRequest $request)
	{
        $cartId     = $request->get('cart_id');

        $merchantId = $request->get('merchant_id');

        $productId  = $request->get('product_id');

        $qty        = $request->get('qty');

        $data = $this->cartService->addItemToCart($merchantId, $productId, $qty, $cartId);

        $data->load('items', 'shippingToPartner', 'installationCosts');

        return $this->respondWithData($data);

	}

    public function apiDeleteItem(CartDeleteItemRequest $request, $id)
    {
        $uid = $request->get('uid');

        $data = $this->cartService->removeItem($uid, $id);

        $data->load('items', 'shippingToPartner', 'installationCosts');

        return $this->respondWithData($data);

    }

    public function apiEmptyCart(CartEmptyRequest $request)
    {
        $uid = $request->get('uid');

        $data = $this->cartService->emptyCart($uid);

        $data->load('items', 'shippingToPartner', 'installationCosts');

        return $this->respondWithData($data);
    }


    public function apiUpdateItemQty(CartChangeQtyRequest $request)
    {
        $uid = $request->get('uid');

        $item_id = $request->get('item_id');

        $qty = $request->get('qty');

        $data = $this->cartService->updateItemQuantity($uid, $item_id, $qty);

        $data->load('items', 'shippingToPartner', 'installationCosts');

        return $this->respondWithData($data);

    }

    public function setPaymentMethod(Request $request)
    {

        $paymentMethodId = $request->get('payment_method_id');

        $data = $this->cartService->setPaymentMethod($paymentMethodId);

        $data->load('items', 'shippingToPartner', 'installationCosts');

        return $this->respondWithData($data);
    }

    public function cancelCart($uuid)
    {
        $cart = $this->cartService->cancelCart($uuid);

        return $this->respondWithData($cart);
    }

    public function setPaymentStatus(Request $request)
    {
        $cartId = $request->get('cart_id');
        $paymentStatusId = $request->get('payment_status_id');

        try {
            $this->cartService->setPaymentStatus($cartId, $paymentStatusId);

            return $this->respondWithInfo('OK');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function setCartStatus()
    {
        // TODO: cartStatus is set either automatically or the admin can change it.
        // In this context, it's just a "helper" method, where input is programatically
        // set, not in some form user can interact with
        $cartStatusId = Input::get('cart_status_id');

        $data = $this->cartService->setCartStatus($cartStatusId);

        return $this->respondWithData($data);
    }

    public function setShippingOption()
    {
        $shippingOptionId = Input::get('shipping_option_id');

        $data = $this->cartService->setshippingOption($shippingOptionId);

        return $this->respondWithData($data);
    }

    public function apiSetShippingToPartner(Request $request)
    {
        $shippingToPartnerId = $request->get('partner_id');

        $cart = $this->cartService->setShippingToPartner($shippingToPartnerId);

        if ($cart){
            $cart->load('items', 'owner', 'installationCosts', 'shippingToAddress.city');
        }
        return $this->respondWithData($cart);
    }

    public function apiSetShippingToAddress(Request $request)
    {
        $shippingToAddressId = $request->get('address_id');

        $cart = $this->cartService->setShippingToAddress($shippingToAddressId);

        if ($cart){
            $cart->load('items', 'owner', 'installationCosts', 'shippingToAddress.city');
        }

        return $this->respondWithData($cart);
    }

    public function setShippingMethod()
    {
        $shippingMethodId = Input::get('shipping_method_id');

        $data = $this->cartService->setShippingMethod($shippingMethodId);

        return $this->respondWithData($data);
    }

    public function delete()
    {
        $data = $this->cartService->delete();

        return $this->respondWithData($data);
    }

    public function getAll()
    {
        // TODO: maybe something like:
        // $userId = Auth::user()->user_id;
        // $data = $this->cartService->getAllForUser($userId);

        $data = $this->cartService->getAll();

        return $this->respondWithData($data);
    }

    public function getAllForUser()
    {
        // TODO: which one?
        $userId = Input::get('user_id');
        // $userId = Auth::user()->user_id;

        $data = $this->cartService->getAllForUser($userId);

        return $this->respondWithData($data);
    }

    public function getAllForPartner($partner_id, $onlyOpened='open')
    {
        $data = $this->cartService->getAllForPartner($partner_id, $onlyOpened);

        return $this->respondWithData($data);
    }

    public function costs(Request $request){
        $cart = $request->get('cart');
        $memberId = empty($cart['member_id'])?null:$cart['member_id'];

        $data['shipping']['amount_without_tax']=0;
        $data['shipping']['tax_amount']=0;
        $data['shipping']['amount_with_tax']=0;
        $data['shipping']['courier_price'] = 0;
        $items= array_key_exists('items', $cart)?$cart['items']:null;

        $courierShippingPrice = (new ShippingCalculator(ShippingMethod::COURIER_PAYABLE))->getShippingCost($items);

        $shippingMethodId = empty($cart['shipping_method_id'])?null:$cart['shipping_method_id'];
        $shippingOptionId = empty($cart['shipping_option_id'])?null:$cart['shipping_option_id'];
        $shippingToPartnerId = empty($cart['shipping_to_partner_id'])?null:$cart['shipping_to_partner_id'];


        if ($shippingMethodId==ShippingMethod::COURIER_PAYABLE){
            $data['shipping']['amount_without_tax']=$courierShippingPrice['amount_without_tax'];
            $data['shipping']['tax_amount']=$courierShippingPrice['tax_amount'];
            $data['shipping']['amount_with_tax']=$courierShippingPrice['amount_with_tax'];
        }

        $data['shipping']['courier_price'] = $courierShippingPrice['amount_with_tax'];


        $installationCosts = CartInstallationCost::calculate($items, $memberId);

        $data['installation'] = $installationCosts;

        $data['available_payment_methods'] = MemberPaymentMethod::availableByShipping($shippingOptionId, $shippingMethodId, $shippingToPartnerId);
        return json_encode($data);
    }

    public function index($status){

        return view('admin.cart.index', compact('status'));
    }
}
