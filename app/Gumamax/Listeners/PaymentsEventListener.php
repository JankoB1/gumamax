<?php

namespace Gumamax\Listeners;

use App\Models\User;
use Delmax\Cart\Models\Order;
use Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Gumamax\Mailers\UserMailer;

class PaymentsEventListener
{
    /**
     * @var UserMailer
     */
    private $mailer;

    /**
     * @var UserActivityRepositoryInterface
     */
    private $userActivityRepo;

    /**
     * Create the event listener.
     *
     * @return void
     * @param UserMailer $mailer
     * @param UserActivityRepositoryInterface $userActivityRepo
     */
    public function __construct(UserMailer $mailer, UserActivityRepositoryInterface $userActivityRepo)
    {
        $this->mailer = $mailer;
        $this->userActivityRepo = $userActivityRepo;
    }

    /**
     * Log payment method change.
     *
     * @param User $user
     * @param Order $order
     * @param int $old_payment_method_id
     * @return void
     */
    public function onOrderPaymentMethodChange(User $user, Order $order, $old_payment_method_id){

        $payload = sprintf('Order: %d; New payment_method_id: %d; Old payment_method_id: %d', $order->id, 
                    $order->payment_method_id, $old_payment_method_id);

        $this->userActivityRepo->logGmx($user, 'PaymentMethodChanged', [$payload]);
    }

    /**
     * Send email to user on unsuccessful payment.
     *
     * @param Order $order
     * @param StdClass $status
     * @return void
     */
    public function onPaymentMessage(Order $order, $status, $force_send_notification = null) { 
        
        if (empty($order->notification_mail_sent) || !empty($force_send_notification)) {

            if ($status->payment_successful) {
                $this->mailer->sendSuccessfulPaymentMessage($order, $status);
            } else {
                $this->mailer->sendUnsuccessfulPaymentMessage($order, $status);
            }

            $order->notification_mail_sent = 1;
            $order->save();
        }
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
