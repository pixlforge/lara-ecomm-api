<?php

namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderPaymentSuccessful
{
    use Dispatchable, SerializesModels;

    /**
     * The order property.
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
