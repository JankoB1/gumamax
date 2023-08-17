<?php namespace Delmax\Listeners;


use App\Models\User;
use Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface;
use Gumamax\Mailers\UserMailer;
use Gumamax\Users\Events\UserEvent;

class UserEventListener {


    /**
     * @var UserMailer
     */
    private $mailer;
    /**
     * @var UserActivityRepositoryInterface
     */
    private $userActivityRepo;

    public function __construct(UserMailer $mailer, UserActivityRepositoryInterface $userActivityRepo){

        $this->mailer = $mailer;
        $this->userActivityRepo = $userActivityRepo;
    }

    public function onUserRegistered(UserEvent $event)
    {

        $this->userActivityRepo->log($event->user, 'RegisteredNewUser');

        $this->mailer->sendWelcomeMessageTo($event->user);

    }

    /**
     * Handle user login events.
     * @param User $user
     */
    public function onUserLogin(User $user)
    {
        $this->userActivityRepo->log($user, 'LoggedIn');
    }

    /**
     * Handle user logout events.
     * @param User $user
     */
    public function onUserLogout(User $user)
    {
        $this->userActivityRepo->log($user, 'LoggedOut');

    }

}
