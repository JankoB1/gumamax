<?php
/**
 * Created by PhpStorm.
 * User: Bane
 * Date: 8.10.2015
 * Time: 14:51
 */

namespace Delmax\Products;

use Illuminate\Database\Eloquent\Model;

class BetterPrice extends Model{

    protected $connection = 'delmax_catalog';

    protected $table = 'product_better_price';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_email',
        'shop_name',
        'shop_phone_number',
        'shop_web',
        'price',
        'description'
    ];

    public static function make(array $data) {

        $betterPrice = new static ($data);

        return $betterPrice;
    }
}