<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.9.2016
 * Time: 14:15
 */

namespace Delmax\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderPayment extends Model
{
    protected $connection = 'ApiDB';

    protected $table='order_payment';

    protected $fillable = [
        'payment_method_id',
        'order_id',
        'user_id',
        'payment_id',
        'backoffice_payment_type_id',
        'date',
        'description',
        'amount'
    ];

    public static function getApiDataTablesData($orderId) {

        return DB::connection('ApiDB')->select("
            select 
                op.id,
                op.user_id,
                date_format(op.date, '%d.%m.%Y') as date,
                op.description,
                op.amount,
                op.created_at,
                pm.description as payment_method
            from order_payment op                
                join delmax_crm.payment_method pm on op.payment_method_id=pm.id
            where op.order_id=". $orderId);
    }
}