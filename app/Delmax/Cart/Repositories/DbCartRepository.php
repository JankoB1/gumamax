<?php namespace Delmax\Cart\Repositories;

use Delmax\Cart\Interfaces\CartItemsRepositoryInterface;
use Delmax\Cart\Models\Cart;
use Delmax\Cart\Interfaces\CartRepositoryInterface;
use Delmax\Cart\Models\CartStatus;
use Gumamax\Partners\CartInstallationCost;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 1:09
 */


class DbCartRepository implements CartRepositoryInterface {
    /**
     * @var Cart $cart
     */
    public $cart;

    /**
     * @var CartItemsRepositoryInterface
     */
    private $itemsRepository;

    /**
     * @param Cart $cart
     * @param CartItemsRepositoryInterface $itemsRepository
     */
    public function __construct(Cart $cart,
        CartItemsRepositoryInterface $itemsRepository)
    {
        $this->cart = $cart;

        $this->itemsRepository = $itemsRepository;
    }

    public function create(Array $data)
    {
        $data['cart_status_id'] = CartStatus::OPEN;

        $this->cart = Cart::create($data);

        return $this->cart;

    }

    public function delete($user, $cartId)
    {
        // +++TODO: Implement delete() method.
    }

    public function cancelCart($user, $cartId)
    {
        // +++TODO: Implement cancelCart() method.
    }

    public function getAllForUser($admin, $user)
    {
        // +++TODO: Implement getAllForUser() method.
    }

    public function getAll($user)
    {
        // +++TODO: Implement getAll() method.
    }

    public function setPaymentStatus($user, $cartId, $paymentStatusId)
    {
        // +++TODO: Implement setPaymentStatus() method.
    }

    public function setCartStatus($user, $cartId, $cartStatusId)
    {
        // +++TODO: Implement setCartStatus() method.
    }

    public function setShippingOption($user, $cartId, $shippingOptionId)
    {
        // +++TODO: Implement setShippingOption() method.
    }

    public function setShippingToPartner($user, $cartId, $partnerId)
    {
        // +++TODO: Implement setShippingToPartner() method.
    }

    public function setShippingToAddress($user, $cartId, $addressId)
    {
        // +++TODO: Implement setShippingToAddress() method.
    }

    public function setShippingMethod($user, $cartId, $shippingMethodId)
    {
        // +++TODO: Implement setShippingMethod() method.
    }

    public function setPaymentMethod($user, $cartId, $paymentMethodId)
    {
        // +++TODO: Implement setPaymentMethod() method.
    }

    public function sendNotification($user, $cartId)
    {
        // TODO: Implement sendNotification() method.
    }

    public function findOpenByUid($uid)
    {
       return Cart::where('uid', $uid)->where('cart_status_id', CartStatus::OPEN)->firstOrFail();

    }

    public function findOpenByUserId($userId)
    {
        return Cart::where('user_id', $userId)->where('cart_status_id', CartStatus::OPEN)->firstOrFail();
    }

    public function find($cartId){

        $cart = Cart::findOrFail($cartId);

        return $cart;
    }

    public function updateOrCreateItem(Array $data){

        $this->itemsRepository->updateOrCreateItem($data);

        $this->cart->setSummary();

        return $this->cart;

    }

    public function setSummary()
    {
        $this->resetSummary();

        foreach ($this->cart->items as $item) {
            $this->cart->items_count ++;
            $this->cart->amount_with_tax          += $item->amount_with_tax;
            $this->cart->tax_amount               += $item->tax_amount;
            $this->cart->amount_without_tax          += $item->amount_without_tax;
            $this->cart->discount_amount          += $item->discount_amount;
            $this->cart->weight                   += $item->weight;
            $this->cart->shipping_amount_without_tax += $item->shipping_amount_without_tax ;
            $this->cart->shipping_tax_amount      += $item->shipping_tax_amount ;
            $this->cart->shipping_amount_with_tax += $item->shipping_amount_with_tax ;
            $this->cart->total_amount_without_tax    += $item->total_amount_without_tax ;
            $this->cart->total_tax_amount         += $item->total_tax_amount ;
            $this->cart->total_amount_with_tax    += $item->total_amount_with_tax ;
            $this->cart->total_qty                += $item->qty ;
            $this->cart->total_old_qty            += $item->old_qty;
        }

        $this->setInstallationCosts();
        $this->setShippingCost();
        $this->cart->save();

    }

    private function resetSummary(){

        $this->cart->items_count=0;
        $this->cart->amount_with_tax=0;
        $this->cart->tax_amount =0;
        $this->cart->amount_without_tax=0;
        $this->cart->discount_amount=0;
        $this->cart->weight=0;
        $this->cart->shipping_amount_without_tax=0;
        $this->cart->shipping_tax_amount=0;
        $this->cart->shipping_amount_with_tax=0;
        $this->cart->total_amount_without_tax=0;
        $this->cart->total_tax_amount=0;
        $this->cart->total_amount_with_tax=0;
        $this->cart->total_qty=0;
        $this->cart->total_old_qty=0;
    }

    public function setInstallationCosts(){

        $costs = CartInstallationCost::calculate($this->cart->items, $this->cart->shipping_to_partner_id);

        $costsModel = CartInstallationCost::findOrNew($this->cart->id);

        $costsModel->cart_id = $this->cart->id;
        $costsModel->alu = $costs['alu'];
        $costsModel->cel = $costs['cel'];

        $costsModel->save();

    }

    public function setShippingCost(){
        $shippingCost = $this->calculateShippingCost();
        foreach($shippingCost as $shippingCostItem){
            $item = CartItem::find($shippingCostItem['ref_cart_item_id']);
            if (!is_null($item)){
                $item->shipping_erp_service_id    = $shippingCostItem['erp_service_id'];
                $item->shipping_amount_without_tax   = $shippingCostItem['amount_without_tax'];
                $item->shipping_tax_amount        = $shippingCostItem['tax_amount'];
                $item->shipping_amount_with_tax   = $shippingCostItem['amount_with_tax'];

                $item->total_amount_without_tax      = $item->amount_without_tax + $shippingCostItem['amount_without_tax'];
                $item->total_tax_amount           = $item->tax_amount      + $shippingCostItem['tax_amount'];
                $item->total_amount_with_tax      = $item->amount_with_tax + $shippingCostItem['amount_with_tax'];
                $item->save();
            }
        }
    }

    public function calculateShippingCost(){
        $result =[];
        $sc = new ShippingCalculator();
        $shippingMethod = ShippingMethod::find($this->cart->shipping_method_id);
        $cartItems = $this->items();
        $i=0;
        foreach($cartItems as $cartItem){
            $calc = $sc->calculateAmount($shippingMethod->courier_id, $shippingMethod->service_id, $cartItem->product_id,$cartItem->qty, $cartItem->weight);
            $result[$i] = $calc;
            $result[$i]['ref_cart_item_id'] = $cartItem->cart_item_id;
            $i++;
        }

        return $result;
    }

}
