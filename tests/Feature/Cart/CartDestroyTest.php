<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ProductVariation;

class CartDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->deleteJson(route('cart.destroy', 1));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_fails_if_the_product_variation_cannot_be_found()
    {
        $user = factory(User::class)->create();

        $response = $this->deleteJsonAs($user, route('cart.destroy', 999));

        $response->assertNotFound();
    }

    /** @test */
    public function it_deletes_an_item_from_the_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $variation = factory(ProductVariation::class)->create()
        );

        $this->assertCount(1, $user->cart);

        $response = $this->deleteJsonAs($user, route('cart.destroy', $variation->id));

        $response->assertOk();

        $this->assertCount(0, $user->fresh()->cart);
    }
}
