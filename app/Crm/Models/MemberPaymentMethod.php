<?php namespace Crm\Models;

use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MemberPaymentMethod extends Model{

    use SoftDeletes;

    protected $connection = 'CRM';

    protected $table = 'member_payment_method';

    protected $primaryKey = 'id';

    protected $fillable = ['member_id', 'payment_method_id'];
    

    public static function availableByShipping($shipping_option_id, $shipping_method_id, $shipping_partner_id){

        /*if (auth()->check() && ( 
            (auth()->user()->first_name == 'BankaTest' && auth()->user()->last_name == 'BankaTest') ||
            auth()->user()->email == 'branimir.vukosavljevic@delmax.rs')) {
            $result = DB::connection('CRM')->select("
                select id as payment_method_id, description, 1 as is_default, icon
                from payment_method
                where id in (4, 5)");
        } elseif */
        if (($shipping_option_id==ShippingOption::CUSTOM_ADDRESS) ||
           (($shipping_option_id==ShippingOption::DELMAX_PARTNER)&&($shipping_method_id==ShippingMethod::COURIER_PAYABLE)))
        {
            $result = DB::connection('CRM')->select("
                select id as payment_method_id, description, is_default, icon
                from payment_method
                where id in (4, 5) and is_active=1
                order by `order`");
        } else {
            $id = (int)$shipping_partner_id;
            $result = DB::connection('CRM')->select("
                select payment_method_id, description, is_default, icon
                from (
                    select id as payment_method_id, description, is_default, `order`, icon
                    from payment_method
                    where id in (4, 5) and is_active=1
                    union
                    select pm.id as payment_method_id, pm.description, pm.is_default, pm.`order`, pm.icon
                    from member_payment_method mpm
                    join payment_method pm on pm.id=mpm.payment_method_id and pm.is_active=1
                    join member m on m.id=mpm.member_id and m.deleted_at is null                       
                    where m.erp_partner_id= {$id} and mpm.deleted_at is null and pm.deleted_at is null and pm.is_active
                    order by `order`) pm");
        }
        return $result;
    }

    public function method(){
        return $this->hasOne(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

}