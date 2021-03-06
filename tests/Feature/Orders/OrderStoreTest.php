<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Address;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;
use Stripe\Customer;

class OrderStoreTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $stripeCustomer = Customer::create([
            'email' => $this->user->email
        ]);

        $this->user->update([
            'gateway_customer_id' => $stripeCustomer->id
        ]);

        $this->country = factory(Country::class)->create();

        $this->country->shippingMethods()->attach(
            $this->shippingMethod = factory(ShippingMethod::class)->create()
        );

        $this->user->addresses()->save(
            $this->address = factory(Address::class)->make([
                'country_id' => $this->country->id
            ])
        );

        $this->user->paymentMethods()->save(
            $this->paymentMethod = factory(PaymentMethod::class)->make()
        );

        $this->user->cart()->sync($this->productWithStock());
    }
    
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->postJson(route('orders.store'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_requires_an_address()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'));

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_an_address_that_exists()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => 999
        ]);

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_an_address_that_belongs_to_the_user()
    {
        $address = factory(Address::class)->create();

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $address->id
        ]);

        $response->assertJsonValidationErrors(['address_id']);
    }

    /** @test */
    public function it_requires_a_shipping_method()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'));

        $response->assertJsonValidationErrors(['shipping_method_id']);
    }

    /** @test */
    public function it_requires_a_shipping_method_that_exists()
    {
        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'shipping_method_id' => 999
        ]);

        $response->assertJsonValidationErrors(['shipping_method_id']);
    }

    /** @test */
    public function it_requires_a_shipping_method_that_is_valid_for_the_given_address()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'shipping_method_id' => $shippingMethod->id
        ]);

        $response->assertJsonValidationErrors(['shipping_method_id']);
    }

    /** @test */
    public function it_requires_a_payment_method_that_belongs_to_the_user()
    {
        $paymentMethod = factory(PaymentMethod::class)->create();

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'payment_method_id' => $paymentMethod->id
        ]);

        $response->assertJsonValidationErrors(['payment_method_id']);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        $this->user->cart()->sync($this->productWithStock());
        
        $response = $this->postJsonAs($this->user, route('orders.store'), $payload = [
            'address_id' => $this->address->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method_id' => $this->paymentMethod->id
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', array_merge($payload, [
            'user_id' => $this->user->id
        ]));
    }

    /** @test */
    public function it_attaches_the_products_to_the_order()
    {
        $this->user->cart()->sync($variation = $this->productWithStock());

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $this->address->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method_id' => $this->paymentMethod->id
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('product_variation_order', [
            'order_id' => $response->getData()->data->id,
            'product_variation_id' => $variation->id,
        ]);
    }

    /** @test */
    public function it_cannot_create_an_order_when_the_cart_is_empty()
    {
        $this->user->cart()->detach();

        $this->user->cart()->attach(($this->productWithStock())->id, [
                'quantity' => 0
        ]);

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $this->address->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method_id' => $this->paymentMethod->id
        ]);

        $response->assertStatus(400);

        $this->assertCount(0, Order::get());
    }

    /** @test */
    public function it_fires_an_order_created_event()
    {
        Event::fake(OrderCreated::class);
        
        $this->user->cart()->sync($this->productWithStock());

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $this->address->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method_id' => $this->paymentMethod->id
        ]);

        $response->assertSuccessful();

        Event::assertDispatched(OrderCreated::class, function ($event) use ($response) {
            return $event->order->id === $response->getData()->data->id;
        });
    }

    /** @test */
    public function it_empties_the_cart_when_ordering()
    {
        $this->user->cart()->sync($this->productWithStock());

        $this->assertNotEmpty($this->user->cart);

        $response = $this->postJsonAs($this->user, route('orders.store'), [
            'address_id' => $this->address->id,
            'shipping_method_id' => $this->shippingMethod->id,
            'payment_method_id' => $this->paymentMethod->id
        ]);

        $response->assertSuccessful();

        $this->assertEmpty($this->user->fresh()->cart);
    }

    /**
     * Create a product variation along with some stocks.
     *
     * @return ProductVariation
     */
    protected function productWithStock()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->create()
        );

        return $variation;
    }
}
