<?php

namespace Tests\Unit\Models\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Money\Money;
use App\Models\Order;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;

class OrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->shippingMethod = factory(ShippingMethod::class)->create([
            'price' => 1000
        ]);

        $this->shippingMethod->orders()->save(
            $this->order = factory(Order::class)->make([
                'subtotal' => 1000
            ])
        );
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->order->user);
    }

    /** @test */
    public function it_belongs_to_an_address()
    {
        $this->assertInstanceOf(Address::class, $this->order->address);
    }

    /** @test */
    public function it_belongs_to_a_shipping_method()
    {
        $this->assertInstanceOf(ShippingMethod::class, $this->order->shippingMethod);
    }

    /** @test */
    public function it_belongs_to_a_payment_method()
    {
        $this->assertInstanceOf(PaymentMethod::class, $this->order->paymentMethod);
    }

    /** @test */
    public function it_has_a_default_status_of_pending()
    {
        $this->assertEquals(Order::PENDING, $this->order->status);
    }

    /** @test */
    public function it_has_many_product_variations()
    {
        $this->order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );
        
        $this->assertInstanceOf(ProductVariation::class, $this->order->products->first());
    }

    /** @test */
    public function it_has_a_quantity_attached_to_the_product_variations()
    {
        $this->order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => $quantity = 2
            ]
        );

        $this->assertEquals($quantity, $this->order->products->first()->pivot->quantity);
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_subtotal()
    {
        $this->assertInstanceOf(Money::class, $this->order->subtotal);
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_total()
    {
        $this->assertInstanceOf(Money::class, $this->order->total());
    }

    /** @test */
    public function it_adds_shipping_onto_the_total()
    {
        $this->assertEquals(2000, $this->order->total()->getAmount());
    }
}
