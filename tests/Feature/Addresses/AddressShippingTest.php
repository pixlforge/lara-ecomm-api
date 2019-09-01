<?php

namespace Tests\Feature\Addresses;

use App\Http\Resources\ShippingMethods\ShippingMethodResource;
use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->addresses()->save(
            $this->address = factory(Address::class)->make()
        );

        $this->address->country->shippingMethods()->attach(
            factory(ShippingMethod::class)->create()
        );
    }

    /** @test */
    public function it_fails_if_unauthorized()
    {
        $response = $this->getJson(route('addresses.shipping', $this->address));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_fails_if_the_address_cannot_be_found()
    {
        $response = $this->getJsonAs($this->user, route('addresses.shipping', 999));

        $response->assertNotFound();
    }

    /** @test */
    public function it_fails_if_the_user_does_not_own_the_address()
    {
        $address = factory(Address::class)->create();

        $response = $this->getJsonAs($this->user, route('addresses.shipping', $address));

        $response->assertForbidden();
    }

    /** @test */
    public function it_shows_shipping_methods_for_the_given_address()
    {
        $response = $this->getJsonAs($this->user, route('addresses.shipping', $this->address));

        $response->assertResource(ShippingMethodResource::collection($this->address->country->shippingMethods));
    }
}
