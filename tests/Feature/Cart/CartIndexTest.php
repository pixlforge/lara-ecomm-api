<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ProductVariation;

class CartIndexTest extends TestCase
{
    use RefreshDatabase;

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
}
