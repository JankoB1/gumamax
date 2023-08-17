<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.9.2016
 * Time: 11:12
 */

namespace Delmax\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    const REQUESTED = 1;
    const PAID = 2;
    const UNPAID = 3;
    const FUNDS_RESERVED = 1100;
    const PAYMENT_DEADLINE_EXPIRED = 1200;
    const CARD_DECLINED_ON_RESERVATION = 1300;
    const CARD_DECLINED_ON_PAYMENT = 1350;
    const PARTIAL_PAYMENT = 2100;
    const PAYMENT_ON_SPOT = 2200;


    protected $connection = 'ApiDB';

    protected $table = 'payment_status';

    protected $primaryKey = 'payment_status_id';

}