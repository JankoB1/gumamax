<?php namespace Delmax\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingOption extends Model{
    use SoftDeletes;

    const DELMAX_PARTNER = 1;
    const CUSTOM_ADDRESS = 2;

    protected  $connection='ApiDB';

    protected $table='shipping_option';

    protected $primaryKey='shipping_option_id';

    public static function defaultShippingOption()
    {
        $option = ShippingOption::where('is_default', 1)->first();

        return (is_null($option)) ? null : $option->shipping_option_id;
    }
}