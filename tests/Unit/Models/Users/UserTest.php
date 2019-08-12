<?php

namespace Tests\Unit\Models\Users;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class UserTest extends TestCase
{
    /** @test */
    public function it_hashes_the_password_when_creating()
    {
        $user = factory(User::class)->create([
            'password' => $password = 'password'
        ]);

        $this->assertNotEquals($password, $user->password);
    }

    /** @test */
    public function it_has_many_cart_product_variations()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    /** @test */
    public function it_has_a_quantity_for_each_product_variation_in_the_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create(),
            ['quantity' => $quantity = 7]
        );

        $this->assertEquals($quantity, $user->cart->first()->pivot->quantity);
    }
}
