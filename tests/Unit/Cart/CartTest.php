<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Money\Money;
use App\Models\ProductVariation;

class CartTest extends TestCase
{
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

        $this->assertCount($quantity, $user->fresh()->cart);
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

    /** @test */
    public function it_can_update_product_variation_quantities_in_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $variation = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->assertEquals(1, $user->cart->first()->pivot->quantity);

        $cart->update($variation->id, 2);

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }

    /** @test */
    public function it_can_delete_a_product_variation_from_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $variation = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->assertCount(1, $user->cart);

        $cart->delete($variation->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    public function it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->sync(
            factory(ProductVariation::class)->create()
        );

        $this->assertCount(1, $user->cart);

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    public function it_can_check_if_the_cart_is_empty_in_terms_of_items()
    {
        $cart = new Cart(
            factory(User::class)->create()
        );

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function it_can_check_if_the_cart_is_empty_in_terms_of_quantity()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 0
            ]
        );

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subtotal());
    }

    /** @test */
    public function it_returns_the_correct_amount_for_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->save(
            factory(ProductVariation::class)->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(2000, $cart->subtotal()->getAmount());
    }

    /** @test */
    public function it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }
}
