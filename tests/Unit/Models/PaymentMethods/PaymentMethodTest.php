<?php

namespace Tests\Unit\Models\PaymentMethods;

use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;

class PaymentMethodTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->paymentMethods()->save(
            $this->paymentMethod = factory(PaymentMethod::class)->make()
        );
    }
    
    /** @test */
    public function it_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->paymentMethod->user);
    }

    /** @test */
    public function it_sets_old_payment_methods_to_not_default_when_creating()
    {
        $this->assertTrue($this->user->paymentMethods->first()->default);

        $this->user->paymentMethods()->save(
            $newPaymentMethod = factory(PaymentMethod::class)->make()
        );

        $this->assertTrue($this->user->paymentMethods->first()->default);

        $this->assertTrue($newPaymentMethod->default);
    }
}
