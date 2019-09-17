<?php

namespace App\Listeners\Orders;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Orders\OrderPaymentSuccessful;

class MarkOrderProcessing implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param OrderPaymentSuccessful $event
     * @return void
     */
    public function handle(OrderPaymentSuccessful $event)
    {
        $event->order->update([
            'status' => Order::PROCESSING
        ]);
    }
}
