<?php 

namespace Gumamax\Listeners;

use Delmax\Models\CallbackRequest;
use Gumamax\Mailers\SupportMailer;

class CallbackRequestEventListener {

    /**
     * @var SupportMailer
     */
    private $mailer;

    public function __construct(SupportMailer $mailer){

        $this->mailer = $mailer;    
    }


    public function onCreated(CallbackRequest $callbackRequest) {

        $this->mailer->sendMessageForCallbackRequest($callbackRequest);

    }
}
