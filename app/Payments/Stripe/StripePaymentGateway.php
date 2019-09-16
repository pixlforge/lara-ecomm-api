<?php

namespace App\Payments\Stripe;

use App\Models\User;
use App\Payments\Contracts\PaymentGatewayContract;

class StripePaymentGateway implements PaymentGatewayContract
{
    /**
     * The user property.
     *
     * @var User $user
     */
    protected $user;
    
    /**
     * Inject a User in behalf of which to operate.
     *
     * @param User $user
     * @return void
     */
    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Create a new Customer resource over on Stripe.
     *
     * @return void
     */
    public function createCustomer()
    {
        return new StripeCustomer();
    }
}