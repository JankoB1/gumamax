<?php namespace Delmax\Addresses;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 9.8.2015
 * Time: 11:59
 */


class AddressType extends Model
{
    const ACCOUNT_OWNER                 =   '5'; //Main address for user account
    const BILLING_AND_GOODS_DELIVERY    =   '1';
    const GOODS_DELIVERY                =   '2';
    const BILLING_DELIVERY              =   '3';
    const GUMAMAX_PARTNER               =   '4';

    protected $connection = 'CRM';

    protected $table = 'address_type';

    protected $primaryKey = 'id';

}
