<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProductVariation;

class CartStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->postJson(route('cart.store'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_requires_products()
    {
        $user = factory(User::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'));

        $response->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function it_requires_products_to_be_an_array()
    {
        $user = factory(User::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => 1
        ]);

        $response->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function it_requires_each_product_variation_to_have_an_id()
    {
        $user = factory(User::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'quantity' => 1
                ]
            ]
        ]);

        $response->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    public function it_requires_each_product_variation_to_exist()
    {
        $user = factory(User::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'id' => 999
                ]
            ]
        ]);

        $response->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    public function it_requires_each_product_variation_to_have_a_quantity()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'id' => $variation->id
                ]
            ]
        ]);

        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    public function it_requires_each_product_variation_to_have_a_numeric_quantity()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'id' => $variation->id,
                    'quantity' => 'one'
                ]
            ]
        ]);

        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    public function it_requires_each_product_variation_quantity_to_be_at_least_one()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'id' => $variation->id,
                    'quantity' => 0
                ]
            ]
        ]);

        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    public function it_can_add_product_variations_to_the_users_cart()
    {
        $user = factory(User::class)->create();

        $variation = factory(ProductVariation::class)->create();

        $response = $this->postJsonAs($user, route('cart.store'), [
            'products' => [
                [
                    'id' => $variation->id,
                    'quantity' => $quantity = 7
                ]
            ]
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('cart_user', [
            'user_id' => $user->id,
            'product_variation_id' => $variation->id,
            'quantity' => $quantity
        ]);
    }
}
