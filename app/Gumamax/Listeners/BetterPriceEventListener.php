<?php namespace Gumamax\Listeners;

use Gumamax\Mailers\SupportMailer;
use Delmax\Products\BetterPrice;

class BetterPriceEventListener {

    /**
     * @var SupportMailer
     */
    private $mailer;

    public function __construct(SupportMailer $mailer){

        $this->mailer = $mailer;    
    }


    public function onCreated(BetterPrice $betterPrice) {

        $this->mailer->sendMessageForBetterPrice($betterPrice);

    }
}