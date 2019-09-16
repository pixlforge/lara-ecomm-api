<?php

namespace App\Payments\Stripe;

use App\Models\PaymentMethod;
use App\Payments\Contracts\CustomerContract;

class StripeCustomer implements CustomerContract
{
    /**
     * Charge a customer.
     *
     * @param PaymentMethod $paymentMethod
     * @param int $amount
     * @return void
     */
    public function charge(PaymentMethod $paymentMethod, $amount)
    {
        //
    }

    /**
     * Add a new card.
     *
     * @param string $token
     * @return void
     */
    public function addCard($token)
    {
        //
    }
}