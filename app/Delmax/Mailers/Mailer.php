<?php namespace Delmax\Mailers;

use Illuminate\Mail\Mailer as Mail;

abstract class Mailer {

    /**
     * @var Mail
     */
    private $mail;

    /**
     * @param Mail $mail
     */
    function __construct(Mail $mail)
    {
        $this->mail = $mail;

    }

    /**
     * @param $user
     * @param $subject
     * @param $view string|array of html template, txt template
     * @param $data
     */
    public function sendTo($user, $subject, $view, $data = [], $attachments = [])
    {

        $this->mail->send(
            $view,
            $data,
            function($message) use($user, $subject, $data, $attachments) {
                $fullName = $user->first_name . ' '.$user->last_name;
                $message->to($user->email, $fullName)->subject($subject);

                foreach ($attachments as $attach){
                    $message->attach($attach['path_to_file'], ['as' => $attach['display_name'], 'mime' => $attach['mime_type']]);
                }

        });
    }

    /**
     * @param $subject
     * @param $view string|array of html template, txt template
     * @param $data
     */
    public function sendToSupport($subject, $view, $data = [])
    {

        $this->mail->send(
            $view,
            $data,
            function($message) use($subject, $data)
            {
                $supportEmail = config('gumamax.web_support_email');
                $message->to($supportEmail, 'Gumamax podrska')->subject($subject);
            });
    }

    /**
     * @param $toEmail
     * @param $toName
     * @param $subject
     * @param $view string|array of html template, txt template
     * @param array $data
     */
    public function sendToAddress($toEmail, $toName, $subject, $view, $data = [])
    {

        $this->mail->send(
            $view,
            $data,
            function($message) use($toEmail, $toName, $subject, $data)
            {
                $message->to($toEmail, $toName)->subject($subject);
            });
    }

} 