<?php

namespace Delmax\Models;


use Illuminate\Database\Eloquent\Model;

class BackofficePaymentType extends Model
{
    const 
        PREAUTHORIZATION = 'PA',
        DEBIT = 'DB',
        CREDIT = 'CD',
        CAPTURE = 'CP',        
        REVERSAL = 'RV',        
        REFUND = 'RF';

    protected $connection = 'CRM';

    protected $table = 'backoffice_payment_type';


    public static function getID($short_des) {

        $pt = self::where('short_description', $short_des)->select('id')->first();
        return $pt->id;
    }

}