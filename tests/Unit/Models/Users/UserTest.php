<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
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
        $this->user->cart()->attach(
            factory(ProductVariation::class)->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $this->user->cart->first());
    }

    /** @test */
    public function it_has_a_quantity_for_each_product_variation_in_the_cart()
    {
        $this->user->cart()->attach(
            factory(ProductVariation::class)->create(),
            ['quantity' => $quantity = 7]
        );

        $this->assertEquals($quantity, $this->user->cart->first()->pivot->quantity);
    }

    /** @test */
    public function it_has_many_addresses()
    {
        $this->user->addresses()->save(
            factory(Address::class)->make()
        );

        $this->assertInstanceOf(Address::class, $this->user->addresses->first());
    }

    /** @test */
    public function it_has_many_orders()
    {
        $this->user->orders()->save(
            factory(Order::class)->make()
        );

        $this->assertInstanceOf(Order::class, $this->user->orders->first());
    }
}
