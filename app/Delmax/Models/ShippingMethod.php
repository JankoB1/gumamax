<?php namespace Delmax\Models;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 5.4.2015
 * Time: 8:05
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ShippingMethod extends Model {

    use SoftDeletes;

    const FREE = 1;
    const COURIER_PAYABLE = 2;

    const ERP_SHIPPING_ARTIKAL_ID = 28724;

    protected $connection = 'ApiDB';
    protected $table = 'shipping_method';

    protected $primaryKey = 'shipping_method_id';

    public static function defaultShippingMethod($shipping_option_id)
    {
        $method = ShippingMethod::where('shipping_option_id', $shipping_option_id)->where('is_default', 1)->first();

        return (is_null($method)) ? null : $method->shipping_method_id;
    }


}