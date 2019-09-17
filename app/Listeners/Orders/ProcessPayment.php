<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderCreated;
use App\Payments\Contracts\PaymentGatewayContract;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayment implements ShouldQueue
{
    /**
     * The payment gateway property.
     *
     * @var PaymentGatewayContract $paymentGateway
     */
    protected $paymentGateway;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PaymentGatewayContract $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        //
    }
}
