<?php

namespace App\Http\Controllers;

use Delmax\Cart\Models\Order;
use Delmax\Cart\Services\DelmaxPaymentService;
use Delmax\Models\BackofficePaymentType;
use Delmax\Models\OrderPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BackofficeController extends Controller
{
    /**
     * @var DelmaxPaymentService
     */
    private $paymentService;    
    
    /**
     * 
     * @param DelmaxPaymentService $paymentService 
     * @return void 
     */
    public function __construct(DelmaxPaymentService $paymentService){
      
        $this->paymentService = $paymentService;
    }
    
    public function index($orderId) {

        $order = Order::find($orderId);

        if ($order) {

            $order_payment = OrderPayment::where('order_id', $orderId)->first();

            $transaction = [];

            $transaction = $this->paymentService->getTransactionReport($order->number);

            $transaction_payment_type = BackofficePaymentType::getID($transaction->paymentType); 

            if ($transaction_payment_type != $order_payment->backoffice_payment_type_id) {

                $order_payment->backoffice_payment_type_id = $transaction_payment_type;
                $order_payment->save();
            }

            return view('admin.payment-gateway.backoffice.partials.backoffice-modal-body', compact('order', 'transaction'));
        }
    }

    public function store(Request $request) {

        $rules = [
            'order_id' => 'required',
            'payment_type' => 'required',
            'full_amount' => 'required',
            'amount_type' => 'required',
            'partial_amount' => 'required_if:amount,partial'
        ];

        if ($request->validate($rules)) {

            $req = $request->all();

            $order = Order::where('id', $req['order_id'])->first();
            $order_payment = OrderPayment::where('order_id', $req['order_id'])->first();

            if ($order && $order_payment) {

                $result = null;

                try {
                    $result = $this->paymentService->backofficeOperation($order, $order_payment, $req);
                    return response()->json($result);

                } catch(\Exception $e) {
                    $error = $e->getMessage();
                    Log::error(__CLASS__. __METHOD__. '(): '. $error);

                    return response()->json(compact('error'), 500);
                }
            }
        }
    }
}
