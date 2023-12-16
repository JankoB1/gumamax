<?php namespace Delmax\Cart\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 22.9.2015
 * Time: 18:49
 */


class DelmaxCart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'delmaxcart'; }
}