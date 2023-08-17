<?php namespace Delmax\Shipping;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2/23/14
 * Time: 7:30 AM
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CourierPriceList
 * Klasa za obradu cenovnika transporta
 */

class CourierPriceList {

    private $shippingMethod;

    public function __construct($shippingMethod){

        $this->shippingMethod = $shippingMethod;
    }

    /**
     * Ako nije definisana vrednost za to_value u tabeli price_list uradice coalesce(courier_price_list.to_value, 10000000.00)

     * @param $courier_price_base_id - WEIGHT, PACKAGE
     * @param $value - tezina u kg ili broj paketa
     * @return mixed - cena sa pdv-om
     */

    public function getPrice($courier_price_base_id, $value){
        
        $val = (int) $value;
        
        $row = DB::table('courier')
            ->join('courier_service', 'courier.courier_id', '=', 'courier_service.courier_id')
            ->join('courier_price_list', 'courier_service.courier_service_id', '=', 'courier_price_list.courier_service_id')
            ->join('delmax_catalog.product as product', 'courier_service.erp_service_id', '=', 'product.product_id')
            ->join('delmax_catalog.tax as tax', 'product.tax_id', '=', 'tax.tax_id')
            ->where('courier.courier_id','=', $this->shippingMethod->courier_id)
            ->where('courier_service.courier_price_base_id','=',$courier_price_base_id)
            ->where('courier_service.service_id','=', $this->shippingMethod->service_id)
            ->whereRaw($val .' between courier_price_list.from_value and coalesce(courier_price_list.to_value,10000000.00)')
            ->select(
                'courier.merchant_id',
                'courier_price_list.price_incl_tax',
                'courier_service.erp_service_id',
                'product.tax_id',
                'tax.rate as tax_rate')
            ->first();
        return $row;
   }
}