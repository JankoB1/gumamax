<?php namespace Gumamax\Mailers;

use Delmax\Mailers\Mailer;
use Delmax\Models\CallbackRequest;
use Delmax\Products\BetterPrice;

class SupportMailer extends Mailer {


    public function sendMessageForBetterPrice(BetterPrice $betterPrice) {

        $subject = 'Imate bolju cenu?';
        $view = [
            'emails.betterPrice.html',
            'emails.betterPrice.text',
        ]; 

        $this->sendToAddress(config('gumamax.better_price_support_email'), '', $subject, $view, compact('betterPrice'));        
    }

    public function sendMessageForCallbackRequest(CallbackRequest $callbackRequest) {
     
        $subject = 'Zahtev za tel. poziv';
        $view = [
            'emails.callback.html',
            'emails.callback.text',
        ]; 

        $this->sendToAddress(config('gumamax.web_support_email'), '', $subject, $view, compact('callbackRequest'));
    }
}