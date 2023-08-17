<?php namespace Crm\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Model{

    use SoftDeletes;

    const CASH_ON_LOCATION = 1;
    const CHEQUE_ON_LOCATION = 2;
    const CARDS_ON_LOCATION = 3;
    const BANK_TRANSFER = 4;
    const CARDS_ONLINE = 5;

    protected $connection ='CRM';
    protected $table='payment_method';
    protected $primaryKey='id';


    public static function defaultMethod(){
        $method = DB::connection('CRM')->table('payment_method')
            ->where('is_default','=',1)
            ->first();
        return $method;
    }

    public static function paymentMethodById($id) {
    	return DB::connection('CRM')->table('payment_method')->where('payment_method_id','=',$id)->first();
    }

    public static function availablePaymentMethodById($id) {
        return DB::connection('CRM')->table('payment_method')->where('payment_method_id','=',$id)->first();
    }
}