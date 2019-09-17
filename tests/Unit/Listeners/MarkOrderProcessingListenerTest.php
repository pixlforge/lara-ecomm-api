<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaymentSuccessful;
use App\Listeners\Orders\MarkOrderProcessing;

class MarkOrderProcessingListenerTest extends TestCase
{
    /** @test */
    public function it_marks_an_order_as_processing()
    {
        $event = new OrderPaymentSuccessful(
            $order = factory(Order::class)->create()
        );

        $listener = new MarkOrderProcessing();

        $listener->handle($event);

        $this->assertEquals(Order::PROCESSING, $order->status);
    }
}
