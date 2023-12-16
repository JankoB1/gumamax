<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 21.9.2016
 * Time: 19:18
 */

namespace Gumamax\Listeners;

use Delmax\Cart\Models\Order;
use Gumamax\Mailers\UserMailer;
use Delmax\Webapp\ImageDraw;

class OrdersEventListener
{
    /**
     * @var UserMailer
     */
    private $mailer;

    /**
     * @param UserMailer $mailer
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onOrderCreated(Order $order){

        if ($order->payment_method_id==4){
            ImageDraw::renderPayOrder($order);
        }
                
        $this->mailer->sendOrderConfirmation($order);      
    }
}