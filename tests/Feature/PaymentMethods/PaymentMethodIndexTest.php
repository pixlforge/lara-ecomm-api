<?php

namespace Tests\Feature\PaymentMethods;

use App\Http\Resources\PaymentMethods\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Models\User;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->getJson(route('payment-methods.index'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_a_collection_of_payment_methods()
    {
        $this->user->paymentMethods()->save(
            factory(PaymentMethod::class)->make()
        );

        $response = $this->getJsonAs($this->user, route('payment-methods.index'));

        $response->assertResource(PaymentMethodResource::collection($this->user->paymentMethods));
    }
}
