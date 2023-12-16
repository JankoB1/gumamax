<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.9.2016
 * Time: 5:18
 */

namespace Delmax\PaymentGateway;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Self_;

class OrderPaymentGatewayLog extends Model
{
    use SoftDeletes;

    protected $connection = 'ApiDB';

    protected $table = 'order_payment_gateway_log';

    protected $primaryKey = 'id';

    protected $fillable = ['order_id', 'checkout_id', 'code', 'description', 'body', 'log_segment'];

    
    public function getCreatedAtAttribute($value) {

        return Carbon::parse($value)->format('d.m.Y H:i:s');        
    }


    public static function jsonBodyToTxt($value) {

        $object = json_decode($value, true);

        return self::enumerateProps($object);
    }

    private static function enumerateProps($object, &$ident = '') {

        $result = '';    

        foreach ($object as $key=>$value) {

            if (is_array($object[$key])) {

                $result .= $ident. $key. ":<br>";

                $ident .= '&nbsp;&nbsp;&nbsp;&nbsp;';

                $result .= self::enumerateProps($object[$key], $ident);  

                $ident = substr($ident, 0, -24);

            } else {               

                $result .= $ident. $key. ": ". $value. "<br>";                
            }
        }

        return $result;
    }

}