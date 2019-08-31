<?php

namespace Tests\Unit\Models\Countries;

use Tests\TestCase;
use App\Models\Country;
use App\Models\ShippingMethod;

class CountryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->country = factory(Country::class)->create();
    }

    /** @test */
    public function it_belongs_to_many_shippings_methods()
    {
        $this->country->shippingMethods()->attach(
            factory(ShippingMethod::class)->create()
        );

        $this->assertInstanceOf(ShippingMethod::class, $this->country->shippingMethods->first());
    }
}
