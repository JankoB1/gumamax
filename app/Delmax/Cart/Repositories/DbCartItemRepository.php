<?php namespace Delmax\Cart\Repositories;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 23:14
 */

use Delmax\Cart\Exceptions\CartItemNotFoundException;
use Delmax\Cart\Interfaces\CartItemsRepositoryInterface;
use Delmax\Cart\Models\CartItem;
use Gumamax\Products\Repositories\ProductRepositoryInterface;


class DbCartItemRepository implements CartItemsRepositoryInterface{

    /**
     * @var CartItem
     */
    private $record;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    public function __construct(CartItem $record, ProductRepositoryInterface $productRepo){

        $this->record = $record;
        $this->productRepo = $productRepo;
    }
    public function create(Array $data)
    {
        return $this->record->create($data);
    }

    public function setItemQty($user, $itemId, $qty)
    {
        // TODO: Implement setItemQty() method.
    }

    public function setItemPrice($user, $itemId, $price)
    {
        // TODO: Implement setItemPrice() method.
    }

    public function deleteItem($cart_id, $item_id)
    {
        $item = CartItem::where(['cart_id'=>$cart_id, 'id'=>$item_id])->first();

        if (!$item){

            throw (new CartItemNotFoundException)->setId($item_id);

        }

        $item->delete();

    }

    public function updateQty($cart_id, $item_id, $newQty){

        $item = CartItem::where(['cart_id'=>$cart_id, 'id'=>$item_id])->first();

        if (!$item){

            throw (new CartItemNotFoundException)->setId($item_id);

        }

        $item->old_qty = $item->qty;

        $item->qty = $newQty;

        $productData = $this->getProductData($item->product_id);

        $item->fill($productData);

        $this->calculateItemAmounts($item, $productData);

        $item->save();

        return $item;

    }

    public function updateOrCreateItem(Array $data){

        $productId  = $data['product_id'];

        $cartId     = $data['cart_id'];

        $merchantId = $data['merchant_id'];

        $qty = $data['qty'] ? $data['qty'] : 0;

        $item = CartItem::where(['cart_id'=>$cartId, 'product_id'=>$productId, 'merchant_id'=>$merchantId])->first();

        if (!$item) {

            return $this->insertItem($data);

        }

        $item->qty += $qty;

        $productData = $this->getProductData($productId);

        $item->fill($productData);

        $this->calculateItemAmounts($item, $productData);

        $item->save();

        return $item;
    }

    public function insertItem(Array $data)
    {
        $newItem = new CartItem($data);

        $productData = $this->getProductData($data['product_id']);

        $newItem->fill($productData);

        $this->calculateItemAmounts($newItem, $productData);

        $newItem->save();

        return $newItem;

    }

    /**
     * @param $productId
     * @return array
     * @internal param array $data
     */
    private function getProductData($productId){

        $product = $this->productRepo->findById($productId);

        $data = [];

        $data['description'] = $product['description'];

        $data['additional_description'] = $product['additional_description'];

        $data['manufacturer'] = $product['manufacturer'];

        $data['cat_no'] = $product['cat_no'];

        $data['season'] = $product['season'];

        $data['weight'] = $product['product_weight'];

        $data['vehicle_category'] = $product['vehicle_category'];

        $data['diameter'] = $product['diameter'];

        $data['tax_id'] = $product['tax_id'];

        $data['tax_rate'] = $product['tax_rate'];

        $data['price_incl_tax'] = $product['price_with_tax'];

        $data['discount'] = $product['discount'];

        $data['list_price'] = $product['list_price'];

        $data['super_price'] = $product['super_price'];

        $data['img_xs_url'] = $product['thumbnail_url_40'];

        $data['img_sm_url'] = $product['thumbnail_url_110'];

        $data['img_lg_url'] = $product['image_url'];

        return $data;
    }

    public function findItem($itemId){

        return CartItem::findOrNew($itemId);

    }

    public function findItemByProductId($productId){

        return CartItem::where('product_id', $productId)->first();

    }

    /**
     * @param CartItem $item
     * @param array $productSalesData
     */
    private function calculateItemAmounts(CartItem &$item, Array $productSalesData){

        $list_amount            = $item->qty * $productSalesData['list_price'];
        $amount_incl_tax        = $item->qty * $productSalesData['price_incl_tax'];
        $tax_amount             = $amount_incl_tax * $productSalesData['tax_rate'] / (100+$productSalesData['tax_rate']);
        $amount_excl_tax        = $amount_incl_tax - $tax_amount;
        $discount_amount        = $item->discount > 0 ? $list_amount-$amount_incl_tax : 0;

        $item->list_price       = $productSalesData['list_price'];
        $item->price_incl_tax   = $productSalesData['price_incl_tax'];
        $item->tax_id           = $productSalesData['tax_id'];
        $item->tax_rate         = $productSalesData['tax_rate'];
        $item->tax_amount       = $tax_amount;
        $item->list_amount      = $list_amount;
        $item->amount_incl_tax  = $amount_incl_tax;
        $item->amount_excl_tax  = $amount_excl_tax;
        $item->discount_amount  = $discount_amount;

        $item->total_amount_excl_tax      = $item->amount_excl_tax + $item->shipping_amount_excl_tax;
        $item->total_tax_amount           = $item->tax_amount      + $item->shipping_tax_amount;
        $item->total_amount_incl_tax      = $item->amount_incl_tax + $item->shipping_amount_incl_tax;
    }

}
