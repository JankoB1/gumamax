<?php namespace Delmax\Shipping;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2/22/14
 * Time: 1:25 PM
 */
use Delmax\Models\ShippingMethod;
use Delmax\Products\Product;
use Delmax\Products\ProductNotFoundException;

/**
 * Class ShippingCalculator
 * Obračun troškova transporta
 * hardcodovani kuriri do daljnjeg
 * 1 - Delmax
 * 2 - Brza posta bilo koja (kasnije ce verovatno trebati da se razradi sistem kroz cenovnike za svakog kurira i osnov obracuna
 *Sada imamo obracun po dva osnova po tezini i po komadima
 */

class ShippingCalculator {

    private $courierPriceBase;
    private $courierPriceList;
    private $shippingMethod;

    public function __construct($shippingMethodId){

        $this->shippingMethod   =   ShippingMethod::find($shippingMethodId);

        $this->courierPriceBase = new CourierPriceBase();

        $this->courierPriceList = new CourierPriceList($this->shippingMethod);
    }

    /**
     * Obračun troškova transporta se vrši u zavisnosti od vrste robe i načina transporta
     * Ukoliko je guma za putničko vozilo, bicikl, motocikl ili 4x4 obračunava se cena po komadu
     * Za gume iz teretnog programa i poljoprivredu obračunava se po kilogramu
     * Ukoliko nije pronadjena ispravna dimenzija proizvoda (vrsta vozila) računa se po kilogramu
     *
     * @param $courier_id
     * @param $service_id
     * @param $product_id
     * @param int $qty
     * @param int $weight
     * @throws ProductNotFoundException
     * @internal param $courier_service_id
     * @internal param $shipping_method_id
     * @internal param $address_id
     * @internal param $partner_id
     * @return array price, erp_service_id, amount
     */

    public function calculateAmount($courier_id, $service_id, $product_id, $qty=0, $weight=0){
        $product = Product::find($product_id);
        if (is_null($product))
            throw new ProductNotFoundException($product_id);

        $courier_price_base_id = $this->courierPriceBase->getByProduct($product);

        $merchant_id        = null;
        $price_excl_tax     = null;
        $price_incl_tax     = null;
        $amount_incl_tax    = null;
        $erp_service_id     = null;
        $value              = null;
        $tax_id             = null;
        $tax_rate           = null;
        $tax_amount         = null;
        $amount_excl_tax    = null;

        switch ($courier_price_base_id) {
            case 'PACKAGE':
                $value = $qty;
                $raw = CourierPriceList::getPrice($courier_id, $service_id, $courier_price_base_id, $value);
                if (!is_null($raw)){
                    $merchant_id      = $raw->merchant_id;
                    $price_incl_tax   = $raw->price_incl_tax;
                    $amount_incl_tax  = $qty * $raw->price_incl_tax;
                    $erp_service_id   = $raw->erp_service_id;
                    $tax_id           = $raw->tax_id;
                    $tax_rate         = $raw->tax_rate;

                }
                break;
            default :
                $value =  ($weight>0) ? $weight : $qty * Product::getProductWeight($product_id);
                $raw = CourierPriceList::getPrice($courier_id, $service_id, $courier_price_base_id, $value);
                if (!is_null($raw)){
                    $merchant_id      = $raw->merchant_id;
                    $price_incl_tax   = $raw->price_incl_tax;
                    $amount_incl_tax  = $raw->price_incl_tax;
                    $erp_service_id   = $raw->erp_service_id;
                    $tax_id           = $raw->tax_id;
                    $tax_rate         = $raw->tax_rate;
                }
                break;
            }
        $price_excl_tax     = $price_incl_tax * $tax_rate / (100+$tax_rate);
        $tax_amount         = $amount_incl_tax * $tax_rate / (100+$tax_rate);
        $amount_excl_tax    = $amount_incl_tax - $tax_amount;
        return array(
            'merchant_id'       => $merchant_id,
            'erp_service_id'    => $erp_service_id,
            'qty'               => $value,
            'list_price'        => $price_incl_tax,
            'list_amount'       => $amount_incl_tax,
            'price_excl_tax'    => $price_excl_tax,
            'amount_excl_tax'   => $amount_excl_tax,
            'tax_id'            => $tax_id,
            'tax_rate'          => $tax_rate,
            'tax_amount'        => $tax_amount,
            'price_incl_tax'    => $price_incl_tax,
            'amount_incl_tax'   => $amount_incl_tax,

        );
    }

    /**
     * V2
     * Obračun troškova transporta se vrši u zavisnosti od vrste robe i načina transporta
     * Ukoliko je guma za putničko vozilo, bicikl, motocikl ili 4x4 obračunava se cena po komadu
     * Za gume iz teretnog programa i poljoprivredu obraÄŤunava se po kilogramu
     * Ukoliko nije pronadjena ispravna dimenzija proizvoda (vrsta vozila) računa se po kilogramu
     *
     * @param $cartItem
     * @return array price, erp_service_id, amount
     */

    public function setItemCosts(&$cartItem){

        $costs = $this->getItemCosts($cartItem);

        $cartItem['shipping_amount_with_tax']     = $costs['shipping_amount_with_tax'];
        $cartItem['shipping_amount_without_tax']  = $costs['shipping_amount_without_tax'];
        $cartItem['shipping_tax_amount']          = $costs['shipping_tax_amount'];
        $cartItem['erp_service_id']               = $costs['erp_service_id'];
    }

    public function getItemCosts($cartItem){

        $erp_service_id     = null;
        $value              = null;
        $tax_rate           = 0;
        $tax_id             = null;
        $amount_with_tax    = 0;


        $courier_price_base_id = $this->courierPriceBase->getByDescriptionIdAndVehicleCategory($cartItem['description_id'], $cartItem['vehicle_category']);

        $value = $this->getCalcValue($courier_price_base_id, $cartItem['qty'], $cartItem['weight']);

        $price = $this->courierPriceList->getPrice($courier_price_base_id, $value);

        if (!is_null($price)){
            $tax_id   = $price->tax_id;
            $tax_rate = $price->tax_rate;
            $amount_with_tax = $value * $price->price_incl_tax;
            $erp_service_id  = $price->erp_service_id;
        }

        $tax_amount                    = round($amount_with_tax * $tax_rate  / (100+$tax_rate), 2);
        $amount_without_tax            = $amount_with_tax - $tax_amount;

        $result['tax_id']              = $tax_id;
        $result['amount_with_tax']     = $amount_with_tax;
        $result['amount_without_tax']  = $amount_without_tax;
        $result['tax_amount']          = $tax_amount;
        $result['erp_service_id']      = $erp_service_id;

        return $result;
    }

    private function getCalcValue($courierPriceBaseId, $qty=1, $weight=1){
        $qty = 1;
        $weight = 1;

        switch ($courierPriceBaseId) {
            case 'PACKAGE':
                $value = $qty;
                break;
            default :
                $value =  $qty * $weight;
                break;
        }

        return $value;
    }

    private function getPriceByWeight($courier_id, $weight){
        $price = 0;
        return $price;
    }

    public function calculateByWeight($courier_id, $weight){
        $price = $this->getPriceByWeight($courier_id, $weight);
        $amount = $weight * $price;
        return $amount;
    }

    private function getPriceByQuantity($courier_id, $qty){
        $price = 120;
        return $price;
    }

    public function calculateByQuantity($courier_id, $qty){
        $price = $this->getPriceByQuantity($courier_id, $qty);
        $amount = $qty * $price;
        return $amount;
    }

    public function setShippingCost(&$items){
        foreach($items as &$cartItem){
            $this->setItemCosts($cartItem);
        }
    }

    public function getShippingCost($items){

        $data = [
            'product_id' => ShippingMethod::ERP_SHIPPING_ARTIKAL_ID,
            'description' => 'Prevoz robe',
            'additional_description' => 'brza pošta',
            'amount_with_tax' => 0,
            'amount_without_tax' => 0,
            'tax_amount' => 0
        ];

        if (!is_null($items)){
            foreach($items as &$cartItem){
                $costs = $this->getItemCosts($cartItem);
                $data['amount_with_tax']      += $costs['amount_with_tax'];
                $data['amount_without_tax']   += $costs['amount_without_tax'];
                $data['tax_amount']           += $costs['tax_amount'];
            }
        }

        return $data;
    }

}
