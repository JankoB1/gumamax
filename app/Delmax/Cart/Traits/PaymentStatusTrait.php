<?php
namespace Delmax\Cart\Traits;

use Crm\Models\PaymentMethod;
use Delmax\Cart\Models\Order;
use Delmax\Models\PaymentStatus;
use stdClass;

trait PaymentStatusTrait {

    public function setPaymentStatus($cart_id, $payment_status_id) {

        $order = Order::where('cart_id', $cart_id)->first();

        if ($order) {

            $order->payment_status_id = $payment_status_id;
            $order->save();

            if ($order->payment_method_id != PaymentMethod::CARDS_ONLINE) {
                $status = new stdClass;
                $status->payment_successful = ($payment_status_id == PaymentStatus::PAID) ? true : false;
                event('payment.message', [$order, $status, true]);
            }
        }
    }
}