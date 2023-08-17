<?php

namespace App\Http\Controllers;

use Delmax\Cart\Models\Order;
use Illuminate\Http\Request;
use Delmax\PaymentGateway\OrderPaymentGatewayLog;

class PaymentGatewayController extends DmxBaseController {    
    
    public function index() {

        return view('admin.payment-gateway.index');
    }

    public function apiDatatables()
    {
        $data = Order::getPaymentGatewayLog();

        if ($data) {
            
            return datatables()::of($data)->addColumn('actions', function ($model) {
                return view('admin.payment-gateway.actions', compact('model'));
            })->make(true);
        }
    }

    public function apiDatatablesItems($orderId) {

        $log = OrderPaymentGatewayLog::where('order_id', $orderId)->get();

        foreach ($log as &$item) {

            $item->body = OrderPaymentGatewayLog::jsonBodyToTxt($item->body);
        }       

        if ($log) {

            $d = datatables()::of($log);

            return $d->make(true);
        }
    }
}
