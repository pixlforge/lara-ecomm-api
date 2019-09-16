<?php

namespace App\Payments\Contracts;

use App\Models\User;

interface PaymentGatewayContract
{
    /**
     * Inject a User in behalf of which to operate.
     *
     * @param User $user
     * @return void
     */
    public function withUser(User $user);

    /**
     * Create a new Customer resource over on Stripe.
     *
     * @return void
     */
    public function createCustomer();
}