<?php

namespace Tests\Unit\Models\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;

class AddressTest extends TestCase
{
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
}
