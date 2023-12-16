<?php namespace Delmax\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $connection = 'delmax_catalog';
    
    protected $table = 'cart_item';

    protected $primaryKey = 'id';

    protected $touches = ['cart'];

    protected $fillable =[
        'cart_id',
        'merchant_id',
        'document_id',
        'product_id',
        'description',
        'description_id',
        'additional_description',
        'manufacturer_id',
        'manufacturer',
        'cat_no',
        'season',
        'weight',
        'vehicle_category',
        'diameter',
        'year_of_production',
        'country_of_origin',
        'list_price',
        'list_amount',
        'uom_id',
        'packing',
        'qty',
        'tax_id',
        'tax_rate',
        'tax_amount',
        'price_with_tax',
        'amount_with_tax',
        'discount',
        'discount_amount',
        'shipping_amount_with_tax',
        'shipping_amount_without_tax',
        'shipping_tax_amount',
        'total_amount_without_tax',
        'total_tax_amount',
        'total_amount_with_tax',
        'img_xs_url',
        'img_sm_url',
        'img_lg_url'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

}
