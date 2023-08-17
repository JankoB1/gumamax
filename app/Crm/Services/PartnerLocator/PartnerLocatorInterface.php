<?php namespace Crm\Services\PartnerLocator;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 23.9.2015
 * Time: 9:18
 */
interface PartnerLocatorInterface
{

    public function nearest($latitude, $longitude, $radius, $type, Array $pagination=[]);

}