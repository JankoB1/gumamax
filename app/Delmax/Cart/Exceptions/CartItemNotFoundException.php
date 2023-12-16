<?php namespace Delmax\Cart\Exceptions;

use RuntimeException;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.3.2015
 * Time: 22:07
 */


class CartItemNotFoundException extends RuntimeException {

    /**
     * Cart item identifier of the affected item.
     *
     * @var string
     */
    protected $id;

    /**
     * Set the affected cart item identifier.
     *
     * @param  string   $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        $this->message = "No query results for cart item [{$id}].";

        return $this;
    }

    /**
     * Get the affected cart item identifier.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}