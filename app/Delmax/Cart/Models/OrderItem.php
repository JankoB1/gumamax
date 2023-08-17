<?php namespace Delmax\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 10.9.2016
 * Time: 1:32
 */

class OrderItem extends Model
{
    use SoftDeletes;

    protected $connection = 'delmax_catalog';

    protected $table = 'order_item';

    protected $primaryKey = 'id';

    protected $fillable =[
        'order_id',
        'cart_item_id',
        'erp_item_id',
        'position_number',
        'product_id',
        'description',
        'additional_description',
        'manufacturer',
        'cat_no',
        'uom_id',
        'packing',
        'qty',
        'list_price',
        'list_amount',
        'discount',
        'discount_amount',
        'price_with_tax',
        'tax_id',
        'tax_rate',
        'tax_amount',
        'amount_with_tax',
        'weight'
    ];
}