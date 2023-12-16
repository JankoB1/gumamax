<?php namespace Delmax\Cart\Exceptions;

use RuntimeException;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.9.2015
 * Time: 9:12
 */


class CartNotFoundException extends RuntimeException
{
    /**
     * Cart UID identifier of the affected cart.
     *
     * @var string
     */
    protected $uid;

    /**
     * Set the affected cart Uid.
     *
     * @param  string   $uid
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        $this->message = "No query results for cart uid [{$uid}].";

        return $this;
    }

    /**
     * Get the affected cart UID identifier.
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

}