<?php namespace Delmax\Cart\Interfaces;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 1:08
 */


interface CartRepositoryInterface
{


    public function create(Array $data);

    public function delete($user, $cartId);

    public function cancelCart($user, $cartId);

    public function getAllForUser($admin, $user);

    public function getAll($user);

    public function findOpenByUid($uid);

    public function findOpenByUserId($userId);

    public function find($cartId);


    public function setPaymentStatus($user, $cartId, $paymentStatusId);

    public function setCartStatus($user, $cartId, $cartStatusId);

    public function setShippingOption($user, $cartId, $shippingOptionId);

    public function setShippingToPartner($user, $cartId, $partnerId);

    public function setShippingToAddress($user, $cartId, $addressId);

    public function setShippingMethod($user, $cartId, $shippingMethodId);

    public function setPaymentMethod($user, $cartId, $paymentMethodId);

    public function updateOrCreateItem(Array $data);

    public function setSummary();

    public function setInstallationCosts();
    public function setShippingCost();
    public function calculateShippingCost();


}