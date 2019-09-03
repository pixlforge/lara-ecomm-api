<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\User;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->addresses()->save(
            $this->address = factory(Address::class)->make()
        );
    }
    
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->postJson(route('orders.store'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_requires_an_address()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'));

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_an_address_that_exists()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => 999
        ]);

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_an_address_that_belongs_to_the_user()
    {
        $address = factory(Address::class)->create();

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $address->id
        ]);

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_a_shipping_method()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'));

        $response->assertJsonValidationErrors(['shipping_method_id']);
    }

    /** @test */
    public function it_requires_a_shipping_method_that_exists()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'shipping_method_id' => 999
        ]);

        $response->assertJsonValidationErrors(['shipping_method_id']);
    }
}
