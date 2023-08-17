<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.9.2016
 * Time: 16:03
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Delmax\Cart\Models\Order;
use Delmax\Models\OrderPayment;
use Delmax\Models\PaymentStatus;
use Delmax\Webapp\Models\MerchantApi;
use Yajra\Datatables\Datatables;
use Delmax\Cart\Traits\PaymentStatusTrait;


class OrderPaymentController extends DmxBaseController {

    use PaymentStatusTrait;

    public function index($orderId){

        $order = Order::find($orderId);

    }

    public function create(Request $request){

        $model = new OrderPayment([
            'order_id' => $request->order_id,
            'payment_method_id' => $request->payment_method_id,
            'date' => Carbon::now()->format('d.m.Y H:i:s'),
            'amount' => $request->amount,
            'user_id' => $request->user_id
        ]);

        $formUrl = route('admin.orders-payment.store');

        $formMethod = 'POST';

        return view('admin.orders.payment.edit-modal', compact('model', 'formUrl', 'formMethod'));
    }

    public function store(Request $request) {

        $rules = [
            'date' => 'required',
            'description' => 'required|max:64',
            'amount' => 'required|numeric'
        ];

        if ($request->validate($rules)) {

            $req = $request->all();
            $req['date'] = Carbon::parse($req['date'])->format('Y-m-d');

            $order = Order::find($request->order_id);

            $merchantAPI = MerchantApi::where('merchant_id', 8080)->first();                
            $result = json_decode($merchantAPI->updatePaymentStatus($order->cart_id, PaymentStatus::PAID));

            if (empty($result->error)) { 

                OrderPayment::create($req);   
                $this->setPaymentStatus($order->cart_id, PaymentStatus::PAID); 

            }  else {
                throw new \Exception($result->message);
            } 
        }
    }

    public function apiDatatables($orderId){

        $query = OrderPayment::getApiDataTablesData($orderId);

        $d = datatables()::of($query);

        return $d->make(true);
    }
}