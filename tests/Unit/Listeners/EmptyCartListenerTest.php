<?php

namespace Tests\Unit\Listeners;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use App\Listeners\Orders\EmptyCart;

class EmptyCartListenerTest extends TestCase
{
    /** @test */
    public function it_clears_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $listener = new EmptyCart($cart);
        $listener->handle();

        $this->assertEmpty($user->cart);
    }
}
