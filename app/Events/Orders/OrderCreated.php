<?php

namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;

class OrderCreated implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    /**
     * The order.
     *
     * @var Order $order
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
