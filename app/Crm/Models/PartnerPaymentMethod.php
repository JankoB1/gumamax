<?php namespace Crm\Models;

use Delmax\Models\PaymentMethod;
use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PartnerPaymentMethod extends Model{

    use SoftDeletes;

    protected $connection = 'CRM';

    protected $table='partner_payment_method';

    protected $primaryKey = 'id';

    public static function getListByPartnerId($erpPartnerId){
        $id = (int)$erpPartnerId;
        // Uvek vraca placanje karticom na sajtu cak i ako nije  definisano na partneru i opstom uplatnicom
        $sql = "
            SELECT id, description, 1 AS is_default
			FROM payment_method
			WHERE id = 5 and is_active=1
			UNION
			SELECT id, description, 0 AS is_default
			FROM payment_method
			WHERE id = 4 and is_active=1
			UNION
			SELECT pm.id, pm.description, pm.is_default
			FROM delmax_crm.partner_payment_method pp
			JOIN payment_method pm ON pm.id=pp.payment_method_id and pm.is_active=1
			WHERE pp.erp_partner_id= ? AND pp.deleted_at IS NULL AND pm.deleted_at IS NULL AND NOT (pm.payment_method_id IN (4,5))
			ORDER BY 3 DESC";

        $result = DB::connection('CRM')->select($sql, [$id]);

        return $result;
    }

    public static function getListByShippingParams($shipping_option_id, $shipping_method_id, $shipping_partner_id){
        if (($shipping_option_id==ShippingOption::CUSTOM_ADDRESS)||
            (($shipping_option_id==ShippingOption::DELMAX_PARTNER)&&($shipping_method_id==ShippingMethod::COURIER_PAYABLE)))
        {
            $result = DB::connection('CRM')->select("
                    select id, description, 1 as is_default
                    from payment_method
                    where id in ( 4, 5) and is_active=1");
        }
        else
        {
            $id = (int)$shipping_partner_id;
            $sql = "
                    select id, description , is_default
                    from payment_method
                    where id in (4, 5) and is_active=1
                    union
                    select pm.id, pm.description, pm.is_default
                    from delmax_gumamax.partner_payment_method pp
                      join payment_method pm on pm.id=pp.payment_method_id and pm.is_active=1
                    where pp.erp_partner_id= ? and pp.deleted_at is null and pm.deleted_at is null
                    order by 3 desc, 2 ";

            $result = DB::connection('CRM')->select($sql, [$id]);
        }
        return $result;
    }

    public function method(){
        return $this->hasOne(PaymentMethod::class, 'payment_method_id', 'id');
    }

}