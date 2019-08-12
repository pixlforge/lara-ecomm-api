<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class CartUpdateTest extends TestCase
{
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->patchJson(route('cart.update', 1));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_fails_if_the_product_variation_cannot_be_found()
    {
        $user = factory(User::class)->create();

        $response = $this->patchJsonAs($user, route('cart.update', 999));

        $response->assertNotFound();
    }

    /** @test */
    public function it_requires_a_quantity()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->patchJsonAs($user, route('cart.update', $variation->id));

        $response->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function it_requires_a_numeric_quantity()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->patchJsonAs($user, route('cart.update', $variation->id), [
            'quantity' => 'one'
        ]);

        $response->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function it_requires_a_quantity_of_at_least_1()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->patchJsonAs($user, route('cart.update', $variation->id), [
            'quantity' => 0
        ]);

        $response->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    public function it_updates_the_quantity_in_the_cart()
    {
        $user = factory(User::class)->create();
        
        $user->cart()->attach(
            $variation = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->assertEquals(1, $user->cart->first()->pivot->quantity);

        $response = $this->patchJsonAs($user, route('cart.update', $variation->id), [
            'quantity' => 2
        ]);

        $response->assertOk();

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }
}
