<?php namespace Delmax\Webapp\Models;

use Delmax\Webapp\RestClient;
use Illuminate\Database\Eloquent\Model;
use Exception;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 19.10.2015
 * Time: 5:08
 */


class MerchantApi extends Model
{
    protected $connection   = 'ApiDB';
    protected $table        = 'merchant_api';
    protected $primaryKey   = 'merchant_api_id';

    public function sendCart($cart){

        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd, $this->keep_alive_uri, $this->api_key);

        return $restClient->postCall('/erp/cart', null, $cart);

    }

    public function getHealth(){
        $data = [
            'time_start'    => null,
            'time_end'      => null,
            'time_elapsed'  => null,
            'error'         => null,
            'api_url'       => null,
            'api_keepalive' => null
        ];

        $data['time_start'] = microtime(true);
        try{
            $data['api_url']        = $this->api_url;
            $data['api_keepalive']  = $this->keep_alive_uri;

            $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd, $this->keep_alive_uri);

            if (!$restClient->isAlive()){
                throw new Exception('Merchant server is not alive');
            }

            $data['time_end'] = microtime(true);
            $data['time_elapsed'] = number_format($data['time_end'] - $data['time_start'],3);
            $data['time_start'] = date("H:i:s" , $data['time_start']);

        } catch (Exception $e){
            $data['error'] = $e->getMessage();
        }

        return $data;

    }

    public function cancelCartToErp($data){
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/order/cancel', null, $data);
    }

    public function updatePaymentStatus($cart_id, $payment_status_id) {
        $requestData = ['payment_status_update'=>['cart_id'=>$cart_id, 'payment_status_id'=>$payment_status_id]];
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd, $this->keep_alive_uri, $this->api_key);        
        return $restClient->postCall('/erp/order/payment/status', null, $requestData);
    }

    public function updateCartFromErp($cartId){
        $query = 'cart_id='.$cartId;
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/order/update', $query, null);
    }

    public function stockTiresAvailabilityChanges(){
        $requeestData = array('stock_tires_availability_changes'=>array('company_id'=>'8000', 'merchant_id'=>$this->merchant_id,'period'=>10));
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/stock/tires/availability/changes', null, $requeestData);
    }

    public function stockAvailabilityChanges(){
        $requestData = array('stock_availability_changes'=>array('company_id'=>'8000', 'merchant_id'=>$this->merchant_id, 'period'=>10));
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/stock/availability/changes', null, $requestData);
    }

    public function productChanges($company_id, $period){
        $requestData = ['product_changes'=>['company_id'=>$company_id, 'merchant_id'=>$this->merchant_id, 'period'=>$period]];
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/product/changes/gumamax', null, $requestData);
    }

    public function productRange($company_id, $fromId, $toId){
        $requestData = ['product_range'=>['company_id'=>$company_id, 'merchant_id'=>$this->merchant_id, 'from_id'=>$fromId, 'to_id'=>$toId]];
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd);
        return $restClient->postCall('/erp/product/range/gumamax', null, $requestData);
    }

    public function updatePaymentMethod($cart_id, $payment_method_id){
        $requestData = ['payment_method_update'=>['cart_id'=>$cart_id, 'payment_method_id'=>$payment_method_id]];
        $restClient = new RestClient($this->api_url, $this->api_user, $this->api_pwd, $this->keep_alive_uri, $this->api_key);
        return $restClient->postCall('/erp/order/payment/method', null, $requestData);
    }
}