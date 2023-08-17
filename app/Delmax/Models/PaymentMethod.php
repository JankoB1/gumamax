<?php namespace Delmax\Models;


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

    protected $connection ='ApiDB';
    protected $table = 'payment_method';
    protected $primaryKey = 'payment_method_id';


    public static function getList(){
        return DB::table('payment_method')
        	->whereNull('deleted_at')
        	->orderBy('order')
        	->get();
    }

    public static function getDefaultMethodId(){
        $method = DB::table('payment_method')
            ->where('is_default','=',1)
            ->first();
        return $method->payment_method_id;
    }

    public static function getPaymentMethodById($id) {
    	return DB::table('payment_method')->where('payment_method_id','=',$id)->first();
    }
}