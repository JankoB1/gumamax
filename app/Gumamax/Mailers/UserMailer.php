<?php namespace Gumamax\Mailers;

use Delmax\Cart\Models\Order;
use Delmax\Mailers\Mailer;
use App\Models\User;
use Laravel\File;

class UserMailer extends Mailer {

    /**
     * @param User $user
     */
    public function sendWelcomeMessageTo(User $user)
    {
        $subject = 'Dobrodošli u Gumamax!';
        $view = [
            'account.personal.emails.register-html',
            'account.personal.emails.register-text'
        ];

        $this->sendTo($user, $subject, $view, compact('user'));
    }

    public function sendOrderConfirmation(Order $order){

        $subject = 'gumamax.com - Potvrda porudžbine!';

        $view =[
            'emails.orders.created.html',
            'emails.orders.created.text'
        ];

        $attachments = [];

        $odustanak_od_ugovora = $this->BuildAttachmentData(config('gumamax.docs_path'). 'obrazac_odustanak_od_ugovora.pdf', 'Obrazac za odustanak od ugovora na daljinu');

        if (!empty($odustanak_od_ugovora)) {

            $attachments[] = $odustanak_od_ugovora;
        }

        $zahtev_za_reklamacijom = $this->BuildAttachmentData(config('gumamax.docs_path'). 'ZapisnikReklamacijaProizvodaSaobraznost.doc', 'Zapisnik o reklamaciji proizvoda');

        if (!empty($zahtev_za_reklamacijom)) {

            $attachments[] = $zahtev_za_reklamacijom;
        }

        $this->sendTo($order->user, $subject, $view, compact('order'), $attachments);

    }

    private function BuildAttachmentData($path_to_file, $display_name = '') {

        if (file_exists($path_to_file)) {

            $mime = File::mime(pathinfo($path_to_file, PATHINFO_EXTENSION));

            $result = ['path_to_file'=>$path_to_file, 'mime_type'=>$mime];

            $result['display_name'] = empty($display_name) ? pathinfo($path_to_file, PATHINFO_FILENAME) : $display_name;

            return $result;
        } else {

            return 0;
        }

    }

    public function sendUnsuccessfulPaymentMessage(Order $order, $paymentStatus) {

        $subject = 'Gumamax.com - Neuspešna transakcija';

        $view = [
            'emails.orders.checkout.transaction-fail.html',
            'emails.orders.checkout.transaction-fail.text'
        ];

        $this->sendTo($order->user, $subject, $view, compact('order', 'paymentStatus'));
    }

    public function sendSuccessfulPaymentMessage(Order $order, $paymentStatus) {

        $subject = 'Gumamax.com - Potvrda porudžbine';

        $view = [
            'emails.orders.checkout.transaction-success.html',
            'emails.orders.checkout.transaction-success.text'
        ];

        $this->sendTo($order->user, $subject, $view, compact('order', 'paymentStatus'));
    }
}