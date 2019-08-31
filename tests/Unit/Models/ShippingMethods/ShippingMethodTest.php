<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Models\Country;
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
    public function it_belongs_to_many_countries()
    {
        $this->shippingMethod->countries()->attach(
            factory(Country::class)->create()
        );
        
        $this->assertInstanceOf(Country::class, $this->shippingMethod->countries->first());
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
