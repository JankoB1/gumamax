<?php namespace Delmax\Cart\Services;

use Delmax\Cart\Models\Order;
use Delmax\Models\BackofficePaymentType;
use Delmax\Models\OrderPayment;
use Delmax\Models\PaymentMethod;
use Delmax\Models\PaymentStatus;
use Delmax\PaymentGateway\AllSecure;
use Delmax\PaymentGateway\OrderPaymentGatewayLog;
use Delmax\PaymentGateway\PaymentResultCode;
use Delmax\PaymentGateway\ResultCodesConst;
use Delmax\Webapp\Models\Merchant;
use Illuminate\Support\Facades\Log;

class DelmaxPaymentService
{
    /**
     * 
     * @var AllSecure
     */
    private $paymentGateway;

    /**
     * 
     * @param AllSecure $paymentGateway 
     * @return void 
     */
    public function __construct(AllSecure $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    private function prepareOrder(Order $order) {

        $data = $order->toArray();

        $data['total_amount_with_tax'] = number_format($order->total_amount_with_tax, 2, '.', '');
        $data['shipping_recipient'] = str_replace('&', ' ', $order->shipping_recipient);

        return $data;        
    }

    public function getCheckoutId(Order $order, $paymentType) {

        $rawResponse = $this->paymentGateway->checkoutIdRequest($this->prepareOrder($order), $paymentType);
        $respond = json_decode($rawResponse);
        $this->log($order, $respond, $rawResponse, 'payment - prepare');
        if (property_exists($respond, 'id')){
            $order->checkout_id = $respond->id;
            $order->notification_mail_sent = null;
            $order->save();
        }
        return $respond;
    }

    public function getStatus(Order $order, $resource_path){

        $log = $this->getPaymentGatewayLog($order);

        if ($log) {

           return json_decode($log->body);

        }

        $rawResponse = $this->paymentGateway->checkStatus($resource_path);

        $result = json_decode($rawResponse);

        $this->log($order, $result, $rawResponse, 'payment - result');        

        return $result;
    }

    public function getResultCode(Order $order) {
        
        $result = '';
        
        $log = $this->getPaymentGatewayLog($order);

        if ($log) {
            $result = $log->code;
        }

        return $result;
    }

    private function getPaymentGatewayLog(Order $order) {

        return $order->paymentGatewayLog()
                ->where('checkout_id', $order->checkout_id)
                ->where('code', 'not like', '000.200.%')
                ->first();
    } 

    private function getStatusDescription($status) {

        if (array_key_exists($status->result->code, ResultCodesConst::ERROR_MESSAGES)) {

            $status->result->description = ResultCodesConst::ERROR_MESSAGES[$status->result->code]; 

        }  
    }

    public function processPaymentResultCodes(Order $order, $status){

        if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $status->result->code)){

            $status->payment_successful = true; 
            
            $order->storeOnlinePayment(auth()->user()->user_id, date('Y-m-d'), $status, $order->total_amount_with_tax);

            $this->changePaymentStatus($order, PaymentStatus::FUNDS_RESERVED);

            $result = PaymentResultCode::SUCCESSFUL; 

        } else if (preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $status->result->code)){

            $result = PaymentResultCode::MANUAL_REVIEW;

        } else {
            
            $status->payment_successful = false;
            
            $this->getStatusDescription($status);

            $result = PaymentResultCode::REJECTED;            
        }

        event('payment.message', [$order, $status]);

        return $result;
    }

    public function changePaymentMethod(Order $order, $new_payment_method_id) {

        $resource_path = '';   
        $error = false; 

        if ($new_payment_method_id == PaymentMethod::CARDS_ONLINE) {

            $respond = $this->getCheckoutId($order, 'PA');

            if ($respond) {

                $resource_path = route('checkout.payment.execute', ['order_id'=>$order->id, 'checkout_id'=>$respond->id]);             
            }
            
        } else {

            $old_payment_method_id = $order->payment_method_id;

            $result = $this->sendToERP('updateOrderPaymentMethod', [$order->cart_id, $new_payment_method_id]);
            
            if ($result->error) {

                $error = true;

                throw new \Exception($result->message);

            } else {

                $order->payment_method_id = $new_payment_method_id;
                $order->save();  

                event('order.created', compact('order'));
                
                event('payment.method.changed', [auth()->user(), $order, $old_payment_method_id]);

                $resource_path = route('checkout.payment.instructions', ['order_id'=>$order->id]);
            }
        }

        return compact('resource_path', 'error');
    }

    private function changePaymentStatus(Order $order, $new_payment_status_id) {

        $order->payment_status_id = $new_payment_status_id;
        $order->save();

        $result = $this->sendToERP('updateOrderPaymentStatus', [$order->cart_id, $new_payment_status_id]);

        if ($result->error) {    

            Log::error(__CLASS__. __METHOD__. '(): '. $result->message);
        }
    }

    private function sendToERP($function, Array $params)
    {
        $result = null;

        /** @var Merchant $merchant */
        $merchant = Merchant::find(8080);

        if (!$merchant) {
            throw new \Exception('Merchant does not exists');
        } 

        return json_decode(call_user_func_array([$merchant, $function], $params));        
    }

    private function log(Order $order, $respond, $rawResponse, $log_segment){

        $log = new OrderPaymentGatewayLog();

        $log->checkout_id = $respond->ndc;
        $log->code = $respond->result->code;
        $log->description = $respond->result->description;
        $log->body = $rawResponse;
        $log->log_segment = $log_segment; 
        $order->paymentGatewayLog()->save($log);
    }

    public function getTransactionReport($merchant_transaction_id) {

        $rawResponse = $this->paymentGateway->transactionByMerchantTransactionId($merchant_transaction_id);

        $result = json_decode($rawResponse);
        
        $lastTransactionAction = '';
        $transactionActions = '';
        $shipping = null;

        foreach ($result->payments as $p) {

            if (property_exists($p, 'shipping')) {
                $shipping = $p->shipping;
            }

            if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $p->result->code)) {
                $transactionActions .= $p->paymentType. '->';    
                $lastTransactionAction = $p;  
            }
        }

        $transactionActions = substr($transactionActions, 0, -2);

        $transaction = $lastTransactionAction;
        $transaction->transaction_actions = $transactionActions;
        $transaction->shipping = $shipping;

        return $transaction;        
    }

    public function backofficeOperation(Order $order, OrderPayment $orderPayment, $req_data) {

        $data = [];

        $data['payment_id'] = $orderPayment->payment_id;
        $data['currency'] = $order->currency;
        $data['paymentType'] = $req_data['payment_type'];

        if ($req_data['amount_type'] == 'full') {
            $data['amount'] = $req_data['full_amount'];
        } else {
            $data['amount'] = $req_data['partial_amount'];
        }

        $rawResponse = $this->paymentGateway->backofficeOperation($data); 
        $resp = json_decode($rawResponse);

        $resp->ndc = null;
        $this->log($order, $resp, $rawResponse, 'backoffice - operation: '. $req_data['payment_type']);

        if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $resp->result->code)){

            $orderPayment->backoffice_payment_type_id = BackofficePaymentType::getID($req_data['payment_type']);
            $orderPayment->save();

            return $resp->result;
        } else {

            throw new \Exception($resp->result->description);
        }
    }

    public function getCardProcessorUrl() {

        return $this->paymentGateway->getBaseUrl();
    }
}