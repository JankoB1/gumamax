<?php namespace Delmax\User\Activity\Interfaces;

use App\Models\User;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 5.4.2015
 * Time: 10:00
 */


interface UserActivityRepositoryInterface {

    /**
     * @param User $user
     * @param $description
     * @param array $payload
     * @return void
     */
    public function logGmx(User $user, $description, Array $payload=[]);

    public function logGmxAnonymously($description, Array $payload=[]);


    /**
     * @param User $user
     * @param array $params
     * @return mixed
     */
    public function lastActivity(User $user, Array $params=[]);

    /**
     * @param User $user
     * @param array $params
     * @return mixed
     */
    public function count(User $user, Array $params=[]);


}