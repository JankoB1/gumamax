<?php namespace Delmax\Webapp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 19.10.2015
 * Time: 5:10
 */


class Merchant extends Model
{
    protected $connection   = 'ApiDB';
    protected $table        = 'merchant';
    protected $primaryKey   = 'merchant_id';

    public function api()
    {
        return $this->hasOne(MerchantApi::class, 'merchant_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'city_id', 'city_id');
    }

    public function sendCart($package){

        if (!$this->api)
        {
            throw new \Exception('MerchantApi does not exists');
        }

        return $this->api->sendCart($package);
    }

    public function updateOrderPaymentMethod($cart_id, $payment_method_id) {

        return $this->api->updatePaymentMethod($cart_id, $payment_method_id);
    }

    public function updateOrderPaymentStatus($cart_id, $payment_status_id) {

        return $this->api->updatePaymentStatus($cart_id, $payment_status_id);
    }
}