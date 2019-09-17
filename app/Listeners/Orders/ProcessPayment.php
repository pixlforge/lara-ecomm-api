<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderCreated;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Payments\Contracts\PaymentGatewayContract;
use Exception;
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
        $order = $event->order;

        try {
            $this->paymentGateway->withUser($order->user)
                ->getStripeCustomer()
                ->charge($order->paymentMethod, $order->total()->getAmount());
            
            // Fire a successful event
        } catch (PaymentFailedException $e) {
            OrderPaymentFailed::dispatch($order);
        }
    }
}
