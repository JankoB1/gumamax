<?php namespace Delmax\Cart\Models;

use Gumamax\Partners\PartnerPaymentMethod;
use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Delmax\Partners\Partner;
use Delmax\Addresses\Address;
use Gumamax\Partners\CartInstallationCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;


class Cart extends Model
{
    use SoftDeletes;

    protected $connection = 'delmax_catalog';

    protected $table = 'cart';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'company_id',
        'document_id',
        'partner_id',
        'uuid',
        'description',
        'from_ip',
        'currency',
        'currency_str',
        'cart_status_id',
        'checkout_id',
        'canceled_at',
        'payment_method_id',
        'payment_status_id',

        'shipping_option_id',
        'shipping_method_id',
        'shipping_to_partner_id',

        'shipping_recipient',
        'shipping_address',
        'shipping_address2',
        'shipping_postal_code',
        'shipping_city',
        'shipping_phone',
        'shipping_email',
        'shipping_additional_info',
        'shipping_country_code',
        'shipping_country_iso_alpha_2',
        'shipping_country_iso_alpha_3',
        'shipping_courier_price',

        'billing_recipient',
        'billing_address',
        'billing_address2',
        'billing_postal_code',
        'billing_city',
        'billing_phone',
        'billing_email',
        'billing_additional_info',
        'billing_country_code',

        'total_qty',
        'items_count',

        'list_amount',
        'discount_amount',

        'amount_without_tax',
        'tax_amount',
        'amount_with_tax',

        'shipping_amount_without_tax',
        'shipping_tax_amount',
        'shipping_amount_with_tax',

        'total_amount_without_tax',
        'total_tax_amount',
        'total_amount_with_tax',
        'user_vehicle_id',
        'total_weight',

        'user_first_name',
        'user_last_name',
        'user_email',
        'user_phone_number',
        'user_customer_type_id',
        'user_company_name',
        'user_tax_identification_number',
        'user_erp_partner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function cartStatus(){

        return $this->hasOne(CartStatus::class, 'cart_status_id');

    }

    public function shippingMethod(){

        return $this->hasOne(ShippingMethod::class, 'shipping_method_id');
    }

    public function shippingOption(){

        return $this->hasOne(ShippingOption::class);
    }

    public function shippingToPartner(){

        return $this->hasOne(Partner::class, 'partner_id', 'shipping_to_partner_id');
    }

    public function shippingToAddress(){

        return $this->hasOne(Address::class, 'id', 'shipping_to_address_id');
    }

    public function billingToAddress(){

        return $this->hasOne(Address::class, 'billing_to_address_id');
    }

    public function setSummary(){

        $this->resetSummary();

        foreach ($this->items as $item) {
            $this->items_count ++;
            $this->amount_with_tax          += $item->amount_with_tax;
            $this->tax_amount               += $item->tax_amount;
            $this->amount_without_tax          += $item->amount_without_tax;
            $this->discount_amount          += $item->discount_amount;
            $this->weight                   += $item->weight;
            $this->shipping_amount_without_tax += $item->shipping_amount_without_tax ;
            $this->shipping_tax_amount      += $item->shipping_tax_amount ;
            $this->shipping_amount_with_tax += $item->shipping_amount_with_tax ;
            $this->total_amount_without_tax += $item->total_amount_without_tax ;
            $this->total_tax_amount         += $item->total_tax_amount ;
            $this->total_amount_with_tax    += $item->total_amount_with_tax ;
            $this->total_qty                += $item->qty ;
            $this->total_old_qty            += $item->old_qty;
        }

        $this->setInstallationCosts();
        $this->setShippingCost();
        $this->save();
    }

    private function resetSummary(){

        $this->items_count=0;
        $this->amount_with_tax=0;
        $this->tax_amount =0;
        $this->amount_without_tax=0;
        $this->discount_amount=0;
        $this->weight=0;
        $this->shipping_amount_without_tax=0;
        $this->shipping_tax_amount=0;
        $this->shipping_amount_with_tax=0;
        $this->total_amount_without_tax=0;
        $this->total_tax_amount=0;
        $this->total_amount_with_tax=0;
        $this->total_qty=0;
        $this->total_old_qty=0;
    }

    public function installationCosts(){

        return $this->hasOne(CartInstallationCost::class, 'cart_id');

    }

    public function getAvailablePaymentMethods()
    {
        return PartnerPaymentMethod::getListByShippingParams($this->shipping_option_id, $this->shipping_method_id, $this->shipping_to_partner_id);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
