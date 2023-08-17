<?php namespace Delmax\Cart\Models;

use App\Models\User;
use Carbon\Carbon;
use Crm\Models\Member;
use Delmax\Models\BackofficePaymentType;
use Delmax\Models\OrderPayment;
use Delmax\Models\PaymentMethod;
use Delmax\Models\PaymentStatus;
use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Delmax\PaymentGateway\OrderPaymentGatewayLog;
use Delmax\Webapp\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 10.9.2016
 * Time: 1:32
 */

class Order extends Model
{
    use SoftDeletes;

    protected $connection = 'delmax_catalog';

    protected $table = 'order';

    protected $primaryKey = 'id';

    protected $dates = ['date'];

    protected $appends = ['is_partner_installer'];

    protected $fillable =[
            'merchant_id',
            'cart_id',
            'checkout_id',
            'from_ip',
            'user_id',
            'partner_id',
            'number',
            'date',
            'canceled_at',
            'erp_reference_id',
            'payment_method_id',
            'payment_status_id',
            'currency',
            'currency_str',
            'due_date',
            'tour',
            'dispatch_date',
            'dispatch_time',
            'list_amount',
            'discount_amount',
            'amount_with_tax',
            'amount_without_tax',
            'tax_amount',
            'shipping_amount_without_tax',
            'shipping_tax_amount',
            'shipping_amount_with_tax',
            'total_amount_without_tax',
            'total_tax_amount',
            'total_amount_with_tax',
            'shipping_method_id',
            'shipping_option_id',
            'shipping_to_partner_id',
            'shipping_recipient',
            'shipping_address',
            'shipping_address2',
            'shipping_postal_code',
            'shipping_city',
            'shipping_country_code',
            'shipping_country_iso_alpha_2',
            'shipping_country_iso_alpha_3',
            'shipping_phone',
            'shipping_email',
            'shipping_additional_info',
        /*
        'billing_recipient',
        'billing_address',
        'billing_address2',
        'billing_postal_code',
        'billing_city',
        'billing_country_code',
        'billing_phone',
        'billing_email',
        'billing_additional_info', */

        'user_first_name',
        'user_last_name',
        'user_email',
        'user_phone_number',
        'user_customer_type_id',
        'user_company_name',
        'user_tax_identification_number',
        'user_erp_partner_id',
        'notification_mail_sent'
    ];


    public function items(){

        return $this->hasMany(OrderItem::class);

    }

    public function getDateAttribute($value){

        return Carbon::parse($value)->format('d.m.Y');

    }

    public function getDispatchDateAttribute($value){

        return Carbon::parse($value)->format('d.m.Y');

    }

    public function merchant(){

        return $this->hasOne(Merchant::class, 'merchant_id', 'merchant_id');

    }

    public function user(){

        return $this->hasOne(User::class, 'user_id', 'user_id');

    }

    public function paymentGatewayLog(){

        return $this->hasMany(OrderPaymentGatewayLog::class, 'order_id');

    }

    public function paymentMethod(){

        return $this->hasOne(PaymentMethod::class, 'payment_method_id', 'payment_method_id');

    }

    public function paymentStatus(){

        return $this->hasOne(PaymentStatus::class, 'payment_status_id', 'payment_status_id');

    }


    public function shippingMethod(){

        return $this->hasOne(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');

    }

    public function shippingOption(){

        return $this->hasOne(ShippingOption::class, 'shipping_option_id', 'shipping_option_id');

    }

    public function payments(){

        return $this->hasMany(OrderPayment::class, 'order_id');

    }

    public function storePayment($userId, $paymentMethodId, $date, $status, $amount)
    {
        $payment = new OrderPayment([
            'user_id' => $userId,
            'payment_method_id' => $paymentMethodId,
            'date' => $date,
            'description' => $status->descriptor,
            'payment_id' => $status->id, 
            'backoffice_payment_type_id' => BackofficePaymentType::getID($status->paymentType), 
            'amount' => $amount
        ]);

        return $this->payments()->save($payment);

    }

    public function storeOnlinePayment($userId, $date, $status, $amount)
    {
       return $this->storePayment($userId, 5, $date, $status, $amount);
    }

    public function updatePaymentStatus(){
        $sum =0;

        foreach($this->payments as $payment){

            $sum +=$payment->amount;

        }

        if ($sum>0){

            if ($sum==$this->total_amount_with_tax){

                $this->payment_status_id=2;

            }

        }
    }

    public static function getApiDataTablesData()
    {
        $data = DB::connection('ApiDB')->table('delmax_catalog.order')
            ->join('delmaxapi.payment_status as payment_status', 'order.payment_status_id', '=', 'payment_status.payment_status_id')
            ->join('delmaxapi.payment_method as payment_method', 'order.payment_method_id', '=', 'payment_method.payment_method_id')
            ->select([
                'order.id',
                'order.user_id',
                'order.number',
                DB::raw("date_format(order.date, '%d.%m.%Y') as date"),
                'order.checkout_id',
                'order.cart_id',
                'order.erp_reference_id',
                'order.total_amount_with_tax',
                'order.total_tax_amount',
                'order.total_amount_without_tax',
                'order.discount_amount',
                'order.shipping_to_partner_id',
                'order.shipping_method_id',
                'order.shipping_option_id',
                'order.shipping_recipient',
                'order.shipping_address',
                'order.shipping_postal_code',
                'order.shipping_city',
                'order.shipping_phone',
                'order.payment_status_id',
                'payment_status.description as payment_status',
                'order.payment_method_id',
                'payment_method.description as payment_method',
                'order.user_first_name',
                'order.user_last_name',
                'order.user_customer_type_id',
                'order.user_company_name',
                'order.user_email',
                'order.user_phone_number',
                'order.created_at',
                'order.updated_at']);

        return datatables()::of($data)->addColumn('actions', function ($model) {
            return view('admin.orders.payment.actions', compact('model'));
        })
            ->make(true);
    }

    public static function getUserOrders()
    {
        return DB::connection('delmax_catalog')->select("
            select 
                o.number, 
                o.date,
                o.total_amount_with_tax,  
                o.dispatch_date,
                o.dispatch_time,
                o.shipping_recipient,
                o.shipping_address,
                o.shipping_postal_code,
                o.shipping_city,
                o.shipping_phone,
                o.payment_status_id,
                ps.description as payment_status,
                o.payment_method_id,
                pm.description as payment_method,
                o.created_at
            from `order` o
                join delmax_crm.payment_status ps on o.payment_status_id=ps.payment_status_id
                join delmax_crm.payment_method pm on o.payment_method_id=pm.id
            where user_id=". auth()->user()->user_id);
    }

    public static function getPaymentGatewayLog() {

        return DB::connection("delmax_catalog")->select("
            select distinct
                o.id,
                o.`number`,
                o.user_id,
                date_format(o.date, '%d.%m.%Y') as date,
                o.cart_id,
                o.erp_reference_id,
                o.total_amount_with_tax,
                o.total_tax_amount,
                o.total_amount_without_tax,
                o.discount_amount,               
                o.payment_status_id,
                ps.description as payment_status,
                o.payment_method_id, 
                pm.description as payment_method, 
                pt.short_description as payment_type,  
                op.description as descriptor,           
                o.user_first_name,
                o.user_last_name,
                o.user_customer_type_id,
                o.user_company_name,
                o.user_email,
                o.user_phone_number,
                o.created_at
            from `order` o
                join delmaxapi.order_payment_gateway_log l on l.order_id=o.id
                join delmax_crm.payment_status ps on o.payment_status_id=ps.payment_status_id
                join delmax_crm.payment_method pm on o.payment_method_id=pm.id
                left join delmaxapi.order_payment op on op.order_id=o.id
                left join delmax_crm.backoffice_payment_type pt on pt.id=op.backoffice_payment_type_id");
    }

    public static function apiCount($period)
    {
        switch($period) {
            case 'today':
                return Order::where(['date' => date('Y-m-d')])->count();
                break;
            case 'week':
                return Order::whereBetween('date', [date("Y-m-d", strtotime("-7 day")), date('Y-m-d')])->count();
                break;
            case 'month':
                return Order::whereBetween('date', [date("Y-m-d", strtotime("-1 month")),date('Y-m-d')])->count();
                break;
            case 'year':
                return Order::whereBetween('date', [date("Y-m-d", strtotime("-1 year")), date('Y-m-d')])->count();
                break;
            default:
                return Order::all()->count();
        }

    }

    public function getIsPartnerInstallerAttribute()
    {
        return Member::isInstaller($this->shipping_to_partner_id);
    }
}