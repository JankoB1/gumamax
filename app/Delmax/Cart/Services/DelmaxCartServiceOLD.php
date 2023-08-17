<?php namespace Delmax\Cart\Services;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 22:03
 */
use App\Models\User;
use Delmax\Addresses\Address;
use Delmax\Cart\Exceptions\CartException;
use Delmax\Cart\Exceptions\CartNotFoundException;
use Delmax\Cart\Models\CartItem;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;


class DelmaxCartServiceOLD
{

    public $cartRepo;
    public $cartItemRepo;
    protected $productRepo;
    /**
     * @var Request
     */
    private $request;

    /**
     * @param CartRepositoryInterface $cartRepo
     * @param CartItemsRepositoryInterface $cartItemRepo
     * @param Request $request
     */
    public function __construct(
        CartRepositoryInterface $cartRepo,
        CartItemsRepositoryInterface $cartItemRepo,
        Request $request
    )
    {
        $this->cartRepo = $cartRepo;
        $this->cartItemRepo = $cartItemRepo;
        $this->request = $request;
    }

    public function getUidFromCookie(){

        //return $this->request->cookie(config('dmxcart.cookie_uid_name'));
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

        $cart = $this->cartRepo->create($data);

        $this->setCookie($cookieValue);

        return $cart;
    }

    public function getOpenGuestCart(){
        try {
            $uid = $this->getUidFromCookie();
            $cart = $this->cartRepo->findOpenByUid($uid);
        } catch (ModelNotFoundException $e) {
            $cart = null;
        }

        return $cart;

    }


    /**
     * @return Cart
     */
    public function getOpenUserCart()
    {
        $cart = null;
        if ($user = auth()->user()){
            try {
                    $cart = $this->cartRepo->findOpenByUserId($user->user_id);
                } catch (ModelNotFoundException $e) {
                    $cart = null;
                }
        }

        return $cart;
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
        $cart = $this->getOpened();

        return  $cart ? $cart : $this->create();
    }


    public function emptyCart($uid)
    {
        try {
            $cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $cart->items()->delete();

            $cart->setSummary();

            return $cart;

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
            $cartId = $this->cartRepo->cart->id;
        } else {
            $this->cartRepo->cart = $this->cartRepo->find($cartId);
        }

        $data = $this->initCartItemData();

        $data['cart_id']        = $cartId;
        $data['product_id']     = $product_id;
        $data['merchant_id']    = $merchant_id;
        $data['qty']            = $qty;

        $cart = $this->cartRepo->updateOrCreateItem($data);

        return $cart;

    }

    public function removeItem($uid, $id)
    {
        try {
            $cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $this->cartItemRepo->deleteItem($cart->id, $id);

            $cart->setSummary();

            return $cart;

        } catch (ModelNotFoundException $e) {
            throw new CartItemNotFoundException;
        }
    }

    public function updateItemQuantity($uid, $item_id, $qty)
    {
        try {
            $cart = $this->cartRepo->findOpenByUid($uid);

            if (auth()->check()){

                if (auth()->user()->user_id !== $cart->user_id) {

                    throw new UnauthorizedException;
                }
            }

            $this->cartItemRepo->updateQty($cart->id, $item_id, $qty);

            $cart->setSummary();

            return $cart;

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

    public function mergeUserAndGuestCarts()
    {
        Log::info('merge cart');

        $user = auth()->user();

        $guestCart = $this->getOpenGuestCart();



        if (!is_null($guestCart)){

            $this->mergeAddress($user->user_id, $guestCart);

        }

        $userCart  = $this->getOpenUserCart();

        if (!is_null($guestCart)){

            return $this->mergeCarts($user, $userCart, $guestCart);
        }

        return $userCart;

    }

    public function mergeAddress($userId, Cart $guestCart){

        if (($guestCart)&&($guestCart->shipping_option_id==ShippingOption::CUSTOM_ADDRESS)&&($guestCart->shipping_to_address_id>0)){

            $address = Address::find($guestCart->shipping_to_address_id);

            if ($address){
                $address->addressable_id= $userId;
                $address->addressable_type = 'App\Models\User';
                $address->save();
            }
        }
    }

    public function mergeCarts(User $user, Cart $userCart, Cart $guestCart)
    {
        if (!($guestCart||$userCart)){

            return $this->create();

        }

        if ($guestCart && (!$userCart)){

            $this->setCookie('');

            $user->carts()->save($guestCart);

            return $guestCart;

        }

        if ($guestCart&&$userCart)
        {
            $userCart->shipping_option_id       = $guestCart->shipping_option_id;
            $userCart->shipping_method_id       = $guestCart->shipping_method_id;
            $userCart->shipping_to_partner_id   = $guestCart->shipping_to_partner_id;
            $userCart->shipping_to_address_id   = $guestCart->shipping_to_address_id;
            $userCart->payment_method_id		= $guestCart->payment_method_id;

            $items =  $guestCart->items;

            foreach($items as $guestItem){
                $this->mergeItem($userCart, $guestItem);
            }

            $userCart->setSummary();

            $userCart->save();

            $guestCart->delete();

            return $userCart;
        }

        return $userCart;
    }

    private function mergeItem(Cart $destCart, CartItem $srcItem)
    {
        $this->addItemToCart($srcItem->merchant_id, $srcItem->product_id, $srcItem->qty, $destCart->id);

        $srcItem->delete();
    }

    public function setPaymentMethod($paymentMethodId)
    {

        $cart = $this->getOpened();

        if ($cart) {

            $cart->payment_method_id = $paymentMethodId;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
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
        $cart = $this->getOpened();

        if ($cart) {

            $cart->cart_status_id = CartStatus::CANCELED;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
    }

    public function setPaymentStatus($paymentStatusId)
    {
        $cart = $this->getOpened();

        if ($cart) {

            $cart->payment_status_id = $paymentStatusId;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
    }

    public function setCartStatus($cartStatusId)
    {
        $cart = $this->getOpened();

        if ($cart) {

            $cart->cart_status_id = $cartStatusId;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
    }


    public function setShippingOption($shippingOptionId)
    {
        $cart = $this->getOpened();

        if ($cart) {

            $cart->shipping_option_id = $shippingOptionId;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;

    }

    public function setShippingToPartner($shippingToPartnerId)
    {
        $cart = $this->getOpened();

        if ($cart && $shippingToPartnerId) {

            $cart->shipping_method_id = ShippingMethod::FREE;

            $cart->shipping_option_id = ShippingOption::DELMAX_PARTNER;

            $partner = Partner::find($shippingToPartnerId);

            if ($partner) {

                $cart->shipping_to_partner_id = $shippingToPartnerId;

                $cart->shipping_to_address_id = $partner->gumamaxAddress->first()->id;
            }

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
    }

    public function setShippingToAddress($shippingToAddressId)
    {
        $cart = $this->getOpened();

        if ($cart) {

            $cart->shipping_method_id = ShippingMethod::COURIER_PAYABLE;

            $cart->shipping_option_id = ShippingOption::CUSTOM_ADDRESS;

            $cart->shipping_to_address_id = $shippingToAddressId;

            $cart->shipping_to_partner_id = null;

            $cart->setSummary();

            $cart->save();

        }

        return $cart;
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

        $cart_ids = Cart::whereNull('deleted_at')

            ->where('shipping_to_partner_id','=',$partner_id)

            ->where('shipping_option_id','=',ShippingOption::DELMAX_PARTNER);

        if ($onlyOpened==='open') {

            $cart_ids = $cart_ids->whereNull('ordered_at')->whereNull('order_number');

        }

        $cart_ids = $cart_ids->lists('id')->all();

        foreach ($cart_ids as $cart_id) {

            $opened_carts[] = Cart::find($cart_id);

        }

        return $opened_carts;
    }

    public function validateCart($cartId){

        $cart = Cart::find($cartId);
        $user = auth()->user();
        $this->checkoutValidation($user, $cart);
        return $cart;
    }

    public function sendCart($cart)
    {
        $erpResponse = $this->sendToErp($cart);
        return $this->processErpResponse($cart_id, $erpResponse);
    }

    private function checkoutValidation(User $user, Cart $cart)
    {

        if (is_null($cart))
        {
            throw new CartNotFoundException('Korpa nije pronadjena');
        }

        if ($user->user_id!=$cart->user_id)
        {
            throw new CartException('Možete pristupati samo svojim podacima');
        }

        if ($cart->cart_status_id==CartStatus::CONFIRMED)
        {
            throw new CartException('Korpa je već potvrdjena');
        }

        if ($cart->cart_status_id==CartStatus::CANCELED) {
            throw new CartException('Korpa je već stornirana');
        }

        if (!(in_array($cart->cart_status_id,[CartStatus::OPEN, CartStatus::RECONCILE])))
        {
            throw new CartException('Wrong cart status, cart status must be OPEN or RECONCILE to start checkout');
        }
    }

    private function sendToErp(Cart $cart)
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

        return $this->sendToMerchant($merchant->api, $cart);

    }

    private function sendToMerchant(MerchantApi $merchantApi, Cart $cart)
    {
        return $merchantApi->sendCart($cart);
    }

    private function processErpResponse(Cart $cart, $erpResponse){

        if (is_null($erpResponse))
            throw new Exception('Nema odgovora iz ERP-a');

        $erpResponse = json_decode($erpResponse, true);

        if ($erpResponse['error']) {
            throw new Exception($erpResponse['message']);
        }

        $erpResponse = $erpResponse['value'];

        $responseHeader = (count($erpResponse['header'])>0)?$erpResponse['header'][0]:null;

        if (!is_null($responseHeader)){
            $cart->cart_status_id = $responseHeader['CART_STATUS_ID'];
            $cart->order_id       = $responseHeader['ORDER_ID'];
            $cart->ordered_at     = $responseHeader['ORDERED_AT'];
            $cart->order_number   = $responseHeader['ORDER_NUMBER'];
            $cart->save();
        }

        $hasLiveItems  = false;
        $hasDifference = false;

        foreach($erpResponse['items'] as $erpItem){
            $item_id = $erpItem['CART_ITEM_ID'];
            $cartItem = $this->cartItemRepo->updateQty($cart->id, $item_id, $erpItem['RESERVED_QTY']);
            if ($cartItem){
                $cartItem->stavka_id    = $erpItem['STAVKA_ID'];
                $cartItem->save();
                $diff = ($cartItem->qty < $cartItem->old_qty);
                $hasDifference  = $hasDifference || $diff;
                $hasLiveItems   = $hasLiveItems  || ($cartItem->qty>0);
            }
        }

        $cart->setShippingCost();
        $cart->setSummary();

        if (!$hasDifference)
            /**
             * Everything is OK
             */
            return Redirect::route('pay',array('cart_id'=>$cart_id));
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
