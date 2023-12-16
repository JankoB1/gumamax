<?php namespace Delmax\User\Activity\Repositories;

use Delmax\User\Activity\Interfaces\UserActivityRepositoryInterface;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 5.4.2015
 * Time: 10:03
 */


class EsUserActivityRepositories implements UserActivityRepositoryInterface{


    public function log(User $user, $description, Array $params = [])
    {
        // TODO: Implement log() method.
    }

    public function lastActivity(User $user, Array $params = [])
    {
        // TODO: Implement lastActivity() method.
    }

    /**
     * @param User $user
     * @param array $params
     */
    public function count(User $user, Array $params = [])
    {
        // TODO: Implement count() method.
    }
}