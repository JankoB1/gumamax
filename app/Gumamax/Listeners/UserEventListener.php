<?php namespace Gumamax\Listeners;


use App\Models\User;
use Delmax\Cart\Models\Order;
use Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface;
use Gumamax\Mailers\UserMailer;

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

    public function onUserCreated(User $user)
    {

        $this->userActivityRepo->logGmx($user, 'RegisteredNewUser');

        $this->mailer->sendWelcomeMessageTo($user);

    }

    /**
     * Handle user login events.
     * @param User $user
     */
    public function onUserLogin(User $user)
    {
        $this->userActivityRepo->logGmx($user, 'LoggedIn');
    }

    /**
     * Handle user logout events.
     * @param User $user
     */
    public function onUserLogout(User $user)
    {
        $this->userActivityRepo->logGmx($user, 'LoggedOut');

    }

    public function onCheckoutStarted(User $user, $cart){

        $this->userActivityRepo->logGmx($user, 'CheckoutStarted', $cart);

    }

    public function onOrderCreated(User $user, $order){

        $this->userActivityRepo->logGmx($user, 'OrderCreated', $order);

    }

    public function onPasswordWasReset(User $user){

        $this->userActivityRepo->logGmx($user, 'PasswordWasReset');

    }

    public function onSearch($query){

        if (auth()->check()){
            $this->userActivityRepo->logGmx(auth()->user(), 'Search', $query);
        } else {
            $this->userActivityRepo->logGmxAnonymously('Search', $query);
        }
    }
}