<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Http\Resources\Orders\OrderResource;

class OrderIndexTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
    /** @test */
    public function it_fails_when_unauthenticated()
    {
        $response = $this->getJson(route('orders.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_a_collection_of_orders()
    {
        $this->user->orders()->save(
            factory(Order::class)->make()
        );

        $response = $this->getJsonAs($this->user, route('orders.index'));

        $response->assertResource(OrderResource::collection($this->user->orders));
    }

    /** @test */
    public function it_orders_by_the_latest_first()
    {
        $this->user->orders()->save(
            $order = factory(Order::class)->make()
        );

        $this->user->orders()->save(
            $anotherOrder = factory(Order::class)->make([
                'created_at' => now()->subDay()
            ])
        );

        $response = $this->getJsonAs($this->user, route('orders.index'));

        $response->assertSeeInOrder([
            $order->created_at->toDateTimeString(),
            $anotherOrder->created_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function it_has_pagination_links()
    {
        $response = $this->getJsonAs($this->user, route('orders.index'));

        $response->assertJsonStructure(['meta', 'links']);
    }
}
