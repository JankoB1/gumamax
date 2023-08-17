<?php namespace Delmax\User\Activity\Repositories;

use Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface;
use Delmax\User\Activity\UserActivity;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 5.4.2015
 * Time: 10:02
 */


class DbUserActivityRepository implements UserActivityRepositoryInterface{

    /**
     * @param User $user
     * @param $description
     * @param array $payload
     * @return void
     */
    public function logGmx(User $user, $description, Array $payload = [])
    {
        $activity = UserActivity::logGmxActivity($description, $payload);

        $user->activities()->save($activity);
    }

    public function logGmxAnonymously($description, Array $payload = [])
    {
        UserActivity::logGmxAnonymously($description, $payload);
    }

    /**
     * @param User $user
     * @param array $params
     * @return mixed
     */
    public function lastActivity(User $user, Array $params = [])
    {
        return $user->activities()->select('updated_at')->orderBy('created_at', 'DESC')->first();
    }

    /**
     * @param User $user
     * @param array $params
     */
    public function count(User $user, Array $params = [])
    {
        // TODO: Implement count() method.
        return 0;
    }

}