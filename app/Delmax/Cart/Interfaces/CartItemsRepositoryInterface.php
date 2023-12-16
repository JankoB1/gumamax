<?php namespace Delmax\Cart\Interfaces;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 23:13
 */


interface CartItemsRepositoryInterface {

    public function create(Array $data);

    public function setItemQty($user, $itemId, $qty);

    public function setItemPrice($user, $itemId, $price);

    public function deleteItem($cart_id, $item_id);

    public function insertItem(Array $data);

    public function updateOrCreateItem(Array $data);

    public function updateQty($cart_id, $item_id, $newQty);



}