<?php namespace Gumamax;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 7.10.2016
 * Time: 12:01
 */
class GumamaxApp {

    public function title(){

        $month =date('n');

        $seasonText =  (in_array($month, [3,4,5,6,7,8]))?'Letnje gume za automobile':'Zimske gume za automobile';

        $domainText = subdomain()->inUse()?subdomain()->getName().'.':'';

        return $seasonText .' | '.$domainText.'gumamax.com';
    }

}