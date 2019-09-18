<?php

namespace Tests\Unit\Listeners;

use Mockery;
use Exception;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;
use App\Payments\Stripe\StripeCustomer;
use App\Listeners\Orders\ProcessPayment;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Events\Orders\OrderPaymentSuccessful;
use App\Payments\Stripe\StripePaymentGateway;

class ProcessPaymentListenerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->paymentMethods()->save(
            $this->paymentMethod = factory(PaymentMethod::class)->make()
        );

        $this->user->orders()->save(
            $this->order = factory(Order::class)->make([
                'payment_method_id' => $this->paymentMethod->id
            ])
        );
    }
    
    /** @test */
    public function it_charges_the_correct_payment_method_and_charges_the_correct_amount()
    {
        Event::fake();

        list($event, $paymentGateway, $customer) = $this->mockFlow();

        $customer->shouldReceive('charge')
            ->with(
                $this->order->paymentMethod,
                $this->order->total()->getAmount()
            );

        $listener = new ProcessPayment($paymentGateway);

        $listener->handle($event);
    }

    /** @test */
    public function it_fires_the_order_payment_successful_event()
    {
        Event::fake(OrderPaymentSuccessful::class);

        list($event, $paymentGateway, $customer) = $this->mockFlow();

        $customer->shouldReceive('charge')
            ->with(
                $this->order->paymentMethod,
                $this->order->total()->getAmount()
            );

        $listener = new ProcessPayment($paymentGateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaymentSuccessful::class, function ($event) {
            return $event->order->id === $this->order->id;
        });
    }

    /** @test */
    public function it_fires_the_order_payment_failed_event()
    {
        Event::fake(OrderPaymentFailed::class);

        list($event, $paymentGateway, $customer) = $this->mockFlow();

        $customer->shouldReceive('charge')
            ->with(
                $this->order->paymentMethod,
                $this->order->total()->getAmount()
            )
            ->andThrow(new PaymentFailedException(new Exception()));

        $listener = new ProcessPayment($paymentGateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaymentFailed::class, function ($event) {
            return $event->order->id === $this->order->id;
        });
    }

    /**
     * Mock the payment gateway flow.
     *
     * @return array
     */
    protected function mockFlow()
    {
        $event = new OrderCreated($this->order);

        $paymentGateway = Mockery::mock(StripePaymentGateway::class);

        $paymentGateway->shouldReceive('withUser')
            ->andReturn($paymentGateway)
            ->shouldReceive('getStripeCustomer')
            ->andReturn($customer = Mockery::mock(StripeCustomer::class));
        
        return [$event, $paymentGateway, $customer];
    }
}
