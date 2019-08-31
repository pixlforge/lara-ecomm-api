<?php

namespace Tests\Unit\Models\ShippingMethods;

use Tests\TestCase;
use App\Money\Money;
use App\Models\ShippingMethod;

class ShippingMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->shippingMethod = factory(ShippingMethod::class)->create();
    }
    
    /** @test */
    public function it_returns_a_money_instance_for_the_price()
    {
        $this->assertInstanceOf(Money::class, $this->shippingMethod->price);
    }

    /** @test */
    public function it_returns_a_formatted_price()
    {
        $this->assertEquals(
            (new Money($this->shippingMethod->price->getAmount()))->formatted(),
            $this->shippingMethod->formattedPrice
        );
    }
}
