<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaymentFailed;
use App\Listeners\Orders\MarkOrderPaymentFailed;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    /** @test */
    public function it_marks_an_order_as_payment_failed()
    {
        $event = new OrderPaymentFailed(
            $order = factory(Order::class)->create()
        );

        $listener = new MarkOrderPaymentFailed();
        
        $listener->handle($event);

        $this->assertEquals(Order::PAYMENT_FAILED, $order->status);
    }
}
