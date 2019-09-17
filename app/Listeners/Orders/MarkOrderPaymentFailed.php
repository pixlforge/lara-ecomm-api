<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderPaymentFailed;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkOrderPaymentFailed implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderPaymentFailed $event)
    {
        $event->order->update([
            'status' => Order::PAYMENT_FAILED
        ]);
    }
}
