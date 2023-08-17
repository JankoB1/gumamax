<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 21.9.2016
 * Time: 18:58
 */
namespace Delmax\Cart\Events;


use App\Events\Event;
use Delmax\Cart\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Event
{
    use SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @param  Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}