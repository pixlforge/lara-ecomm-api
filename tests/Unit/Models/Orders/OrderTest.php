<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;

class OrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->order = factory(Order::class)->create();
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
}
