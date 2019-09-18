<?php

namespace App\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTransaction implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->order->transactions()->create([
            'total' => $event->order->total()->getAmount()
        ]);
    }
}
