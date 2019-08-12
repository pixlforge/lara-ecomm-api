<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use App\Money\Money;

class CartIndexTest extends TestCase
{
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->getJson(route('cart.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_shows_products_in_the_users_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $variation = factory(ProductVariation::class)->create(), [
                'quantity' => $quantity = 4
            ]
        );

        $response = $this->getJsonAs($user, route('cart.index'));

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $variation->id,
            'quantity' => $quantity
        ]);
    }

    /** @test */
    public function it_shows_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $response = $this->getJsonAs($user, route('cart.index'));

        $response->assertJsonFragment([
            'isEmpty' => true
        ]);
    }

    /** @test */
    public function it_shows_a_formatted_subtotal()
    {
        $user = factory(User::class)->create();

        $response = $this->getJsonAs($user, route('cart.index'));

        $response->assertJsonFragment([
            'formatted' => (new Money(0))->formatted()
        ]);
    }

    /** @test */
    public function it_shows_a_detailed_subtotal()
    {
        $user = factory(User::class)->create();

        $response = $this->getJsonAs($user, route('cart.index'));

        $response->assertJsonFragment([
            'detailed' => [
                'amount' => '0.00',
                'currency' => 'CHF'
            ]
        ]);
    }
}
