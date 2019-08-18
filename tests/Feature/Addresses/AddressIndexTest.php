<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Tests\TestCase;

class AddressIndexTest extends TestCase
{
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->getJson(route('addresses.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_the_users_addresses()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            $address = factory(Address::class)->make()
        );

        $response = $this->getJsonAs($user, route('addresses.index'));

        $response->assertJsonFragment([
            'id' => $address->id,
            'name' => $address->name
        ]);
    }
}
