<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_products_variations_to_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $variation = factory(ProductVariation::class)->create();

        $cart->add([
            [
                'id' => $variation->id,
                'quantity' => $quantity = 1
            ]
        ]);

        $this->assertCount($quantity, $user->cart);
    }

    /** @test */
    public function it_increments_quantity_when_adding_product_variations_already_present_in_the_cart()
    {
        $variation = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            [
                'id' => $variation->id,
                'quantity' => 1
            ]
        ]);

        $user = $user->fresh();

        $this->assertEquals(1, $user->cart->first()->pivot->quantity);

        $cart = new Cart($user);

        $cart->add([
            [
                'id' => $variation->id,
                'quantity' => 1
            ]
        ]);

        $user = $user->fresh();

        $this->assertEquals(2, $user->cart->first()->pivot->quantity);
    }
}
