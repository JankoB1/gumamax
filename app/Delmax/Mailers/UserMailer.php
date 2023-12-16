<?php namespace Delmax\Mailers;

use App\Models\User;
use Delmax\Cart\Models\Order;
use Delmax\Models\ContactFormMessage;

class UserMailer extends Mailer {

    /**
     * @param User $user
     */
    public function sendWelcomeMessageTo(User $user)
    {
        $subject = 'Welcome to Delmax!';

        $view = 'emails.registration.confirm';

        $this->sendTo($user, $subject, $view);
    }


    public function sendOrderConfirmation(Order $order){

        $subject = 'Potvrda porudžbine!';

        $view =[
            'emails.orders.created.html',
            'emails.orders.created.text'
        ];

        $this->sendTo($order->user, $subject, $view, compact('order'));

    }

    public function sendContactFormMail($data){

        $subject = 'Gumamax kontakt forma';

        $view =[
            'emails.contact.html',
            'emails.contact.text'
        ];

        $this->sendToSupport($subject, $view, $data);

    }

    public function sendReplyToContact(ContactFormMessage $contactFormMessage) {

        $subject = 'Odgovor sa GUMAMAX.COM sajta';

        $view =[
            'emails.contact.reply-html',
            'emails.contact.reply-text'
        ];

        $data['text'] = $contactFormMessage->message;
        $data['answer'] = $contactFormMessage->answer;

        $this->sendToAddress($contactFormMessage->email, $contactFormMessage->name, $subject, $view, $data);

    }

}
