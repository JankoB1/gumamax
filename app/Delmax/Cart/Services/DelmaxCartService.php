<?php namespace Delmax\Cart\Services;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 22:03
 */

use App\Models\User;
use Illuminate\Support\Carbon;
use Crm\Models\Member;
use Crm\Models\PaymentMethod;
use Delmax\Cart\Exceptions\ErpException;
use Delmax\Cart\Models\Cart;
use Delmax\Cart\Models\CartStatus;
use Delmax\Cart\Models\Order;
use Delmax\Models\PaymentStatus;
use Delmax\Models\ShippingOption;
use Delmax\Webapp\Models\Merchant;
use Delmax\Webapp\Traits\CyrToLatTrait;
use stdClass;
use Delmax\Cart\Traits\PaymentStatusTrait;

class DelmaxCartService
{
    use CyrToLatTrait;
    use PaymentStatusTrait;

    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function create(Array $localCart)
    {

        $cart_items = $this->CheckItemsQty($localCart['items']);

        if (empty($cart_items)) {
            return null;
        } else {
            $localCart['items'] = $cart_items;
        }

        $localCart['user_id'] = null;

        if (auth()->user()) {

            $this->setUserData(auth()->user(), $localCart);

        }

        if (subdomain()->inUse()){
            $data['shipping_option_id'] = ShippingOption::DELMAX_PARTNER;
            $data['shipping_to_partner_id'] = subdomain('erp_partner_id');
        }

        $localCart['from_ip']           = getIpAddress();
        $localCart['document_id']       = 508;
        $localCart['company_id']        = '8000';
        $localCart['partner_id']        = '40000';
        $localCart['cart_status_id']    = CartStatus::OPEN;
        $localCart['payment_status_id'] = 1;

        $this->cart = Cart::create($localCart);

        $this->cart->items()->createMany($localCart['items']);

        return $this->cart;
    }

    public function findByUuid($uuid){

        $this->cart = Cart::where('uuid', $uuid)->first();

        return $this->cart;

    }

    public function findById($id){

        $this->cart = Cart::find($id);

        return $this->cart;

    }

    public function sendToERP()
    {
        $result = null;

        /** @var Merchant $merchant */
        $merchant = Merchant::find(8080);

        if (!$merchant) {
            throw new \Exception('Merchant does not exists');
        }

        if ($this->cart) {

            $package = $this->createPackage();

            $response = $merchant->sendCart($package);

            $result = $this->processErpResponse($response);
        }

        return $result;
    }

    /**
    Transaction status
    00.00.00
    first - Global status
    10 - successful
    00 - unsuccessful
    second - Order creation status
    00 - completely reserved
    10 - partially reserved
    20 - nothing reserved
    third - System error
    00 - no error
    10 - communication failed
    12 - erp db unavailable
     */

    public function processErpResponse($response){

        $response = json_decode($response);

        $response->newOrder = null;

        if ($response->error!=null){
            throw new \Exception($response->error);
        }

        if (preg_match('/^00/', $response->status)){

            throw new \Exception('Unsuccessful ERP transaction');

        }

        if ($response->status == '10.00.00'){

            $response->newOrder = $this->createOrder($response);

        }

        return $response;

    }

    public function cancelCart($uuid){

        $cart = $this->findByUuid($uuid);

        if ($cart){
            $cart->canceled_at=Carbon::now();
            $cart->save();
        }
        return $cart;
    }

    private function createOrder($response){

        $erp_order_header = $this->transformResponseOrderHeader($response);

        $user = auth()->user();

        $this->setUserData($user, $erp_order_header);

        $erp_order_items = $this->transformResponseOrderItems($response);

        $order = Order::create($erp_order_header);

        $order->items()->createMany($erp_order_items);

        if ($order->payment_method_id != PaymentMethod::CARDS_ONLINE) {

            event('order.created', compact('order'));

            if ($order->payment_method_id == PaymentMethod::BANK_TRANSFER) {

                $order->payment_due_date = Carbon::addBusinessDay(2);
                $order->save();
            }
        }

        event('user.order.created', [$user, compact('order')]);

        return $order;
    }

    private function transformResponseOrderHeader($response){
        $header = $response->order->header;
        return (array)$header;
    }

    private function transformResponseOrderItems($response){

        $items = [];

        foreach($response->order->items as $item){
            $newItem = (array) $item;
            $items[] = $newItem;
        }

        return $items;

    }

    private function createPackage(){

        $this->cart = $this->cart->fresh();

        $header = $this->cart->toArray();

        $items  = $this->cart->items->toArray();

        $user   = $this->cart->owner;

        $user = $user->load('customer')->toArray();

        $package = ['cart'=>['header'=>$header, 'items'=>$items, 'user'=>$user]];

        return $package;
    }

    private function setUserData(User $user, Array &$cartArray){

        $cartArray['user_id']               = $user->user_id;
        $cartArray['user_first_name']       = $user->first_name;
        $cartArray['user_last_name']        = $user->last_name;
        $cartArray['user_email']            = $user->email;
        $cartArray['user_phone_number']     = $user->phone_number;
        $cartArray['user_customer_type_id'] = $user->customer->customer_type_id;
        $cartArray['user_company_name']     = $user->customer->company_name;
        $cartArray['user_tax_identification_number'] = $user->customer->tax_identification_number;
        $cartArray['user_erp_partner_id']   = $user->customer->erp_partner_id;

    }

    private function CheckItemsQty($items) {

        $result = [];

        foreach($items as $i) {

            if ($i['qty'] != 0) {
                $result[] = $i;
            }
        }

        return $result;
    }


}
