<?php 

namespace Delmax\Cart\Models;

use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    const OPEN = 1;
    const RECONCILE = 2;
    const CONFIRMED = 3;
    const CANCELED = 4;

    protected $connection = 'delmax_catalog';

    protected $table='cart_status';

    protected $primaryKey='cart_status_id';

}
