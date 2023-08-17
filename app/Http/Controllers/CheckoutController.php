<?php namespace App\Http\Controllers;

use Crm\Models\MemberPaymentMethod;
use Delmax\Cart\Models\Order;
use Delmax\Cart\Services\DelmaxCartService;
use Delmax\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Delmax\Cart\Services\DelmaxPaymentService;
use Delmax\PaymentGateway\PaymentResultCode;
use Carbon\Carbon;
use Delmax\Models\BackofficePaymentType;
use Illuminate\Support\Facades\Config;

class CheckoutController extends Controller
{
 
    /**
     * @var DelmaxCartService
     */
    private $cartService;

    /**
     * @var DelmaxPaymentService
     */
    private $paymentService;    

    /**
     * @param DelmaxCartService $cartService
     * @param DelmaxPaymentService $paymentService
     */

    public function __construct(DelmaxCartService $cartService, DelmaxPaymentService $paymentService){

        $this->cartService = $cartService;
        $this->paymentService = $paymentService;
    }

    public function start(Request $request){

        $localCart = $request->get('cart');   

        event('checkout.started', [auth()->user(), $localCart]);

        $cart_id = null;
        $error  = null;
        $erpResult = null;

        $cart = $this->cartService->create($localCart);

        if (is_null($cart)) {

            return ['cart_id'=>$cart_id,
                    'error'=>'No items in cart',
                    'erp_result'=>$erpResult];
        }

        $cart_id = $cart->id;

        try {
            $erpResult = $this->cartService->sendToERP();

            if ($erpResult->newOrder) {
                if ($erpResult->newOrder->payment_method_id == PaymentMethod::CARDS_ONLINE) {
                    $this->paymentService->getCheckoutId($erpResult->newOrder, BackofficePaymentType::PREAUTHORIZATION);
                }
            }

            return ['cart_id'=>$cart_id,
                    'error'=>$error,
                    'erp_result'=>$erpResult];

        } catch(\Exception $e) {
            
            $error = $e->getMessage();
            Log::error(__CLASS__. __METHOD__. '(): '. $error);

            if (!config('app.debug')) {
                $error = 'Nije uspelo kreiranje porudžbenice!';
            }

            return response()->json(compact('error'), 500);         
        }
    }

    public function paymentExecute($order_id, $checkout_id){        

        $order = Order::where(['id'=>$order_id, 'checkout_id'=>$checkout_id, 
            'payment_method_id'=>PaymentMethod::CARDS_ONLINE])->first();

        if ($order) {
        
            $order_items = $order->items()->get();

            $result_code = $this->paymentService->getResultCode($order);

            $cardProcessorUrl = $this->paymentService->getCardProcessorUrl();

            return view('checkout.pay-online.processing', compact('order', 'order_items', 'result_code', 'cardProcessorUrl'));
        }
    }

    public function paymentResult(Request $request){

        $id = $request->get('id');
        $resource_path = $request->get('resourcePath');

        $order = Order::where(['checkout_id'=>$id])->first();

        $status = $this->paymentService->getStatus($order, $resource_path);
        $status->timestamp_local = Carbon::parse($status->timestamp)->timezone(Config::get('app.timezone'))->format('d.m.Y H:i:s');

        $result = $this->paymentService->processPaymentResultCodes($order, $status);

        if ($result == PaymentResultCode::SUCCESSFUL) {

            return view('checkout.pay-online.message-ok', compact('status', 'order'));
        } elseif ($result == PaymentResultCode::MANUAL_REVIEW) {

            return view('checkout.pay-online.message-need-more-actions', compact('status', 'order'));
        } else {
            $available_payment_methods = MemberPaymentMethod::availableByShipping($order->shipping_option_id,
                    $order->shipping_method_id, $order->shipping_to_partner_id);

            return view('checkout.pay-online.message-not-ok', compact('status', 'order', 'available_payment_methods'));
        }
    }    

    public function paymentMethodChange(Request $request) {   

        $order_id = $request->get('order_id');
        $payment_method_id = $request->get('payment_method_id');

        $order = Order::find($order_id);

        if ($order) {

            $result = null;
            
            try {
                $result = $this->paymentService->changePaymentMethod($order, $payment_method_id);

                return response()->json($result);

            } catch (\Exception $e) {

                $error = $e->getMessage();
                Log::error(__CLASS__. __METHOD__. '(): '. $error);

                response()->json($result, 500);
            }
        }
    }

    public function paymentInstructions($order_id){

        $order = Order::find($order_id);

        return view('checkout.result', compact('order'));

    }    

    public function thanks(Request $request){

        $order_id = $request->get('order_id');

        $order = Order::find($order_id);

        return view('checkout.result', compact('order'));

    }
}