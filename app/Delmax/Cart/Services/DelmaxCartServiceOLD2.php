<?php namespace Delmax\Cart\Services;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 22:03
 */
use App\Models\User;
use Delmax\Cart\Exceptions\CartException;
use Delmax\Cart\Exceptions\CartNotFoundException;
use Delmax\Cart\Models\CartStatus;
use Delmax\Partners\Partner;
use Delmax\Webapp\Models\Merchant;
use Delmax\Webapp\Models\MerchantApi;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Delmax\Cart\Exceptions\CartItemNotFoundException;
use Delmax\Cart\Interfaces\CartRepositoryInterface;
use Delmax\Cart\Interfaces\CartItemsRepositoryInterface;
use Delmax\Cart\Models\Cart;
use Delmax\Models\PaymentMethod;
use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;


class DelmaxCartServiceOLD2
{

    public $cartRepo;
    public $cartItemRepo;
    protected $productRepo;
    private $cart;

    /**
     * @param CartRepositoryInterface $cartRepo
     * @param CartItemsRepositoryInterface $cartItemRepo
     */
    public function __construct(
        CartRepositoryInterface $cartRepo,
        CartItemsRepositoryInterface $cartItemRepo
    )
    {
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;
    }

    public function getUidFromCookie(){

        return session(config('dmxcart.cookie_uid_name'));

    }

    public function create()
    {
        $data = $this->initCartData();

        if ($user = auth()->user()) {
            $data['user_id'] = $user->user_id;
            $cookieValue = '';
        } else {
            $cookieValue = $data['uid'];

            if (subdomain()->inUse()){
                $data['shipping_option_id']=ShippingOption::DELMAX_PARTNER;
                $data['shipping_to_partner_id']=subdomain('partner_id');
                $data['shipping_to_address_id']=subdomain('address_id');
            }
        }

        $this->cart = $this->cartRepo->create($data);

        $this->setCookie($cookieValue);

        return $this->cart;
    }

    public function getOpenGuestCart(){
        try {
            $uid = $this->getUidFromCookie();
            $this->cart = $this->cartRepo->findOpenByUid($uid);
        } catch (ModelNotFoundException $e) {
            $this->cart = null;
        }

        return $this->cart;

    }


    /**
     * @return Cart
     */
    public function getOpenUserCart()
    {
        $this->cart = null;
        if ($user = auth()->user()){
            try {
                    $this->cart = $this->cartRepo->findOpenByUserId($user->user_id);
                } catch (ModelNotFoundException $e) {
                    $this->cart = null;
                }
        }

        return $this->cart;
    }

    public function getOpened()
    {
        $userCart = $this->getOpenUserCart();

        if (!$userCart){

            return $this->getOpenGuestCart();

        }

        return $userCart;
    }

    public function getOpenedOrCreate()
    {
        $this->cart = $this->getOpened();

        return  $this->cart ? $this->cart : $this->create();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function emptyCart($uid)
    {
        try {
            $this->cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $this->cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $this->cart->items()->delete();

            $this->cart->setSummary();

            return $this->cart;

        } catch (ModelNotFoundException $e) {
            throw new CartItemNotFoundException;
        }

    }

    /**
     * @param $merchant_id
     * @param $product_id
     * @param int $qty
     * @param null $cartId
     */
    public function addItemToCart($merchant_id, $product_id, $qty = 1, $cartId = null)
    {
        if (!$cartId) {
            $this->cartRepo->cart = $this->getOpenedOrCreate();
            $this->cartId = $this->cartRepo->cart->id;
        } else {
            $this->cartRepo->cart = $this->cartRepo->find($cartId);
        }

        $data = $this->initCartItemData();

        $data['cart_id']        = $cartId;
        $data['product_id']     = $product_id;
        $data['merchant_id']    = $merchant_id;
        $data['qty']            = $qty;

        $this->cart = $this->cartRepo->updateOrCreateItem($data);

        return $this->cart;

    }

    public function removeItem($uid, $id)
    {
        try {
            $this->cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $this->cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $this->cartItemRepo->deleteItem($this->cart->id, $id);

            $this->cart->setSummary();

            return $this->cart;

        } catch (ModelNotFoundException $e) {
            throw new CartItemNotFoundException;
        }
    }

    public function updateItemQuantity($uid, $item_id, $qty)
    {
        try {
            $this->cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $this->cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $this->cartItemRepo->updateQty($this->cart->id, $item_id, $qty);

            $this->cart->setSummary();

            return $this->cart;

        } catch (ModelNotFoundException $e) {
            throw new CartItemNotFoundException;
        }
    }

    protected function generateUid()
    {
        return Hash::make(rand() . uniqid(null, true));
    }

    protected function setCookie($uid)
    {
       $cookie_name = config('dmxcart.cookie_uid_name');
       session([$cookie_name=>$uid]);
    }

    public function setPaymentMethod($paymentMethodId)
    {

        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->payment_method_id = $paymentMethodId;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }

    public function getDefaultShippingMethod($shipping_option_id)
    {

        $method = ShippingMethod::where('shipping_option_id', $shipping_option_id)->where('is_default', 1)->first();

        return (is_null($method)) ? null : $method->shipping_method_id;
    }

    public function getDefaultShippingOption()
    {
        $option = ShippingOption::where('is_default', 1)->first();

        return (is_null($option)) ? null : $option->shipping_option_id;
    }

    public function initCartData(){

        $uid = $this->generateUid();

        $defaultShippingOption = ShippingOption::defaultShippingOption();

        return [
            'uid'                   => $uid,
            'shipping_option_id'    => $defaultShippingOption,
            'payment_method_id'     => PaymentMethod::CARDS_ONLINE,
            'cart_status_id'        => CART_OPEN,
            'shipping_method_id'    => ShippingMethod::defaultShippingMethod($defaultShippingOption),
            'created_at'            => date('Y-m-d H:i:s'),
            'from_ip'               => getIpAddress(),
            'shipping_to_partner_id'=> null,
            'items_count'           => 0,
            'total_qty'             => 0,
            'amount_with_tax'       => 0,
            'amount_without_tax'       => 0,
            'tax_amount'            => 0,
            'discount_amount'       => 0,
            'shipping_amount_with_tax'       => 0,
            'shipping_amount_without_tax'       => 0,
            'shipping_tax_amount'   => 0,
            'total_tax_amount'      => 0,
            'total_amount_with_tax' => 0,
            'total_amount_without_tax' => 0,
        ];
    }

    private function initCartItemData(){
        return [
            'qty'                   => 0,
            'amount_with_tax'       => 0,
            'amount_without_tax'       => 0,
            'tax_amount'            => 0,
            'discount_amount'       => 0,
            'shipping_amount_with_tax'       => 0,
            'shipping_amount_without_tax'       => 0,
            'shipping_tax_amount'   => 0,
            'total_amount_with_tax' => 0,
            'total_amount_without_tax' => 0,
            'list_amount_with_tax'   => 0
        ];
    }


    public function cancelCart()
    {
        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->cart_status_id = CartStatus::CANCELED;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }

    public function setPaymentStatus($paymentStatusId)
    {
        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->payment_status_id = $paymentStatusId;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }

    public function setCartStatus($cartStatusId)
    {
        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->cart_status_id = $this->cartStatusId;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }


    public function setShippingOption($shippingOptionId)
    {
        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->shipping_option_id = $shippingOptionId;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;

    }

    public function setShippingToPartner($shippingToPartnerId)
    {
        $this->cart = $this->getOpened();

        if ($this->cart && $shippingToPartnerId) {

            $this->cart->shipping_method_id = ShippingMethod::FREE;

            $this->cart->shipping_option_id = ShippingOption::DELMAX_PARTNER;

            $partner = Partner::find($shippingToPartnerId);

            if ($partner) {

                $this->cart->shipping_to_partner_id = $shippingToPartnerId;

                $this->cart->shipping_to_address_id = $partner->gumamaxAddress->first()->id;
            }

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }

    public function setShippingToAddress($shippingToAddressId)
    {
        $this->cart = $this->getOpened();

        if ($this->cart) {

            $this->cart->shipping_method_id = ShippingMethod::COURIER_PAYABLE;

            $this->cart->shipping_option_id = ShippingOption::CUSTOM_ADDRESS;

            $this->cart->shipping_to_address_id = $shippingToAddressId;

            $this->cart->shipping_to_partner_id = null;

            $this->cart->setSummary();

            $this->cart->save();

        }

        return $this->cart;
    }

    public function setShippingMethod($shippingMethodId)
    {
        $this->cart = $this->get();

        if ($this->cart) {

            $this->cart->shipping_method_id = $shippingMethodId;

            $this->cart->setSummary();

            $this->cart->save();

            return $this->cart;
        }
    }

    public function delete()
    {
        $this->cart = $this->get();

        if ($this->cart) {

            $this->cart->deleted_at = date('Y-m-d H:i:s');

            $this->cart->setSummary();

            $this->cart->save();

            return $this->cart;
        }

    }

    public function getAll()
    {
        return Cart::whereNull('deleted_at')->get();
    }

    public function getAllForUser($userId)
    {
        return Cart::where('user_id',$userId)

            ->whereNull('deleted_at')

            ->get();
    }

    public function getAllForPartner($partner_id, $onlyOpened)
    {
        $opened_carts = [];

        $this->cart_ids = Cart::whereNull('deleted_at')

            ->where('shipping_to_partner_id','=',$partner_id)

            ->where('shipping_option_id','=',ShippingOption::DELMAX_PARTNER);

        if ($onlyOpened==='open') {

            $this->cart_ids = $this->cart_ids->whereNull('ordered_at')->whereNull('order_number');

        }

        $this->cart_ids = $this->cart_ids->lists('id')->all();

        foreach ($this->cart_ids as $this->cart_id) {

            $opened_carts[] = Cart::find($this->cart_id);

        }

        return $opened_carts;
    }

    public function validateCart($cartId){

        $this->cart = Cart::find($cartId);
        $user = auth()->user();
        $this->checkoutValidation($user, $this->cart);
        return $this->cart;
    }

    public function sendCart()
    {
        $erpResponse = $this->sendToErp($this->cart);

        return $this->processErpResponse($this->cart->id, $erpResponse);
    }

    private function checkoutValidation(User $user)
    {

        if (is_null($this->cart))
        {
            throw new CartNotFoundException('Korpa nije pronadjena');
        }

        if ($user->user_id != $this->cart->user_id)
        {
            throw new CartException('Možete pristupati samo svojim podacima');
        }

        if ($this->cart->cart_status_id==CartStatus::CONFIRMED)
        {
            throw new CartException('Korpa je već potvrdjena');
        }

        if ($this->cart->cart_status_id==CartStatus::CANCELED) {
            throw new CartException('Korpa je već stornirana');
        }

        if (!(in_array($this->cart->cart_status_id,[CartStatus::OPEN, CartStatus::RECONCILE])))
        {
            throw new CartException('Wrong cart status, cart status must be OPEN or RECONCILE to start checkout');
        }
    }

    private function sendToErp()
    {
        $merchant = Merchant::find(8080);

        if (!$merchant)
        {
            throw new Exception('Merchant does not exists');
        }

        if (!$merchant->api)
        {
            throw new Exception('MerchantApi does not exists');
        }

        return $this->sendToMerchant($merchant->api, $this->cart);

    }

    private function sendToMerchant(MerchantApi $merchantApi)
    {
        return $merchantApi->sendCart($this->cart);
    }

    private function processErpResponse($erpResponse){

        if (is_null($erpResponse))
            throw new Exception('Nema odgovora iz ERP-a');

        $erpResponse = json_decode($erpResponse, true);

        if ($erpResponse['error']) {
            throw new Exception($erpResponse['message']);
        }

        $erpResponse = $erpResponse['value'];

        $responseHeader = (count($erpResponse['header'])>0)?$erpResponse['header'][0]:null;

        if (!is_null($responseHeader)){
            $this->cart->cart_status_id = $responseHeader['CART_STATUS_ID'];
            $this->cart->order_id       = $responseHeader['ORDER_ID'];
            $this->cart->ordered_at     = $responseHeader['ORDERED_AT'];
            $this->cart->order_number   = $responseHeader['ORDER_NUMBER'];
            $this->cart->save();
        }

        $hasLiveItems  = false;
        $hasDifference = false;

        foreach($erpResponse['items'] as $erpItem){
            $item_id = $erpItem['CART_ITEM_ID'];
            $this->cartItem = $this->cartItemRepo->updateQty($this->cart->id, $item_id, $erpItem['RESERVED_QTY']);
            if ($this->cartItem){
                $this->cartItem->stavka_id    = $erpItem['STAVKA_ID'];
                $this->cartItem->save();
                $diff = ($this->cartItem->qty < $this->cartItem->old_qty);
                $hasDifference  = $hasDifference || $diff;
                $hasLiveItems   = $hasLiveItems  || ($this->cartItem->qty>0);
            }
        }

        $this->cart->setShippingCost();
        $this->cart->setSummary();

        if (!$hasDifference)
            /**
             * Everything is OK
             */
            return Redirect::route('pay',array('cart_id'=>$this->cart->id));
        else if ($hasLiveItems) {
            /**
             * Need to reconcile
             */
            return Redirect::to('cart')->withInput(Input::all());
        } else {
            /**
             * EmptyResponse
             * No live items
             */
            $newCartId = Cart::newUserCart($this->cart->user_id);
            $newCart = Cart::find($newCartId);
            $newCart->shipping_option_id=$this->cart->shipping_option_id;
            $newCart->shipping_method_id=$this->cart->shipping_method_id;
            $newCart->shipping_to_partner_id=$this->cart->shipping_to_partner_id;
            $newCart->shipping_to_address_id=$this->cart->shipping_to_address_id;
            $newCart->payment_method_id=$this->cart->payment_method_id;
            $newCart->user_vehicle_id=$this->cart->user_vehicle_id;
            $newCart->save();
            return View::make('cart.checkout.empty-response', array('cart'=>$newCart, 'oldCart'=>$this->cart));
        }
    }
}
