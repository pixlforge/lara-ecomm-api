<?php

namespace Tests\Unit\Models\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;

class AddressTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_belongs_to_a_country()
    {
        $country = factory(Country::class)->create();

        $country->addresses()->save(
            $address = factory(Address::class)->make()
        );

        $this->assertInstanceOf(Country::class, $address->country);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            $address = factory(Address::class)->make()
        );

        $this->assertInstanceOf(User::class, $address->user);
    }

    /** @test */
    public function it_sets_other_addresses_to_not_default_when_creating()
    {
        $user = factory(User::class)->create();

        $oldAddress = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => true
        ]);

        $this->assertTrue($oldAddress->isDefault());

        $newAddress = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => true
        ]);

        $this->assertFalse($oldAddress->fresh()->isDefault());

        $this->assertTrue($newAddress->isDefault());
    }
}
