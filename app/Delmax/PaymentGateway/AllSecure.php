<?php namespace Delmax\PaymentGateway;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 6.9.2016
 * Time: 22:47
 */

class AllSecure {

    private $accessToken;
    private $entityId;
    private $baseURL;
    private $SSLVerifyPeer = false;

    public function __construct() {

        $default_config = config('all_secure.default');
        $this->accessToken = config('all_secure.'. $default_config. '.accessToken');
        $this->entityId = config('all_secure.'. $default_config. '.entityId');
        $this->baseURL = config('all_secure.'. $default_config. '.baseURL');

        if ($default_config == 'live') {
            $this->SSLVerifyPeer = true;
        }
    }

    public function checkoutIdRequest(Array $order, $paymentType){
        $url = $this->baseURL. "/v1/checkouts";
        $data = "entityId={$this->entityId}" .
            "&amount={$order['total_amount_with_tax']}" .
            "&currency={$order['currency']}" .
            "&paymentType={$paymentType}".
            "&merchantTransactionId={$order['number']}".
            "&customer.givenName={$order['user_first_name']}".
            "&customer.surname={$order['user_last_name']}".
            "&customer.email={$order['user_email']}".
            "&customer.phone={$order['user_phone_number']}".
            "&customer.ip={$order['from_ip']}".
            "&shipping.street1={$order['shipping_recipient']}" .
            "&shipping.street2={$order['shipping_address']}".
            "&shipping.city={$order['shipping_city']}".
            "&shipping.postcode={$order['shipping_postal_code']}".
            "&shipping.country={$order['shipping_country_iso_alpha_2']}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:Bearer '. $this->accessToken]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSLVerifyPeer);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function checkStatus($resource_path) {

        $url = $this->baseURL. $resource_path. "?entityId={$this->entityId}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:Bearer '. $this->accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSLVerifyPeer);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function transactionByPaymentId($paymentId) {
        $url = $this->baseURL. "/v1/query/{$paymentId}?entityId={$this->entityId}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:Bearer '. $this->accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSLVerifyPeer);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function transactionByMerchantTransactionId($transactionId) {
        $url = $this->baseURL. "/v1/query?entityId={$this->entityId}&merchantTransactionId={$transactionId}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:Bearer '. $this->accessToken]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSLVerifyPeer);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function backofficeOperation($payment_data) {
        $url = $this->baseURL. "/v1/payments/{$payment_data['payment_id']}";
        $data = "entityId={$this->entityId}";

        if (in_array($payment_data['paymentType'], ['CP', 'RF'])) {
            $data .= "&amount={$payment_data['amount']}&currency={$payment_data['currency']}";
        }

        $data .= "&paymentType={$payment_data['paymentType']}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization:Bearer '. $this->accessToken]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->SSLVerifyPeer);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function getBaseUrl() {

        return $this->baseURL;
    }
}
