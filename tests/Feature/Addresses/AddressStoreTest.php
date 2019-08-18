<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;

class AddressStoreTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->postJson(route('addresses.store'));

        $response->assertUnauthorized();
    }
    
    /** @test */
    public function it_requires_a_country_id()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'));

        $response->assertJsonValidationErrors(['country_id']);
    }

    /** @test */
    public function it_requires_a_valid_country_id()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'), [
            'country_id' => 999
        ]);

        $response->assertJsonValidationErrors(['country_id']);
    }

    /** @test */
    public function it_requires_a_name()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'));

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_requires_an_address_1()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'));

        $response->assertJsonValidationErrors(['address_1']);
    }

    /** @test */
    public function it_requires_a_city()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'));

        $response->assertJsonValidationErrors(['city']);
    }

    /** @test */
    public function it_requires_a_postal_code()
    {
        $response = $this->postJsonAs($this->user, route('addresses.store'));

        $response->assertJsonValidationErrors(['postal_code']);
    }
    
    /** @test */
    public function it_stores_an_address()
    {
        $country = factory(Country::class)->create();

        $this->assertCount(0, $this->user->addresses);
        
        $response = $this->postJsonAs($this->user, route('addresses.store'), $payload = [
            'country_id' => $country->id,
            'name' => $this->faker->name,
            'address_1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode
        ]);

        $response->assertSuccessful();

        $this->assertCount(1, $this->user->fresh()->addresses);

        $this->assertDatabaseHas('addresses', array_merge($payload, [
            'user_id' => $this->user->id
        ]));
    }

    /** @test */
    public function it_returns_an_address_resource_after_storing_a_new_address()
    {
        $country = factory(Country::class)->create();

        $response = $this->postJsonAs($this->user, route('addresses.store'), $payload = [
            'country_id' => $country->id,
            'name' => $this->faker->name,
            'address_1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode
        ]);

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'id' => $response->getData()->data->id
        ]);
    }
}
