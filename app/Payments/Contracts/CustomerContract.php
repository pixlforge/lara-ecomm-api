<?php

namespace App\Payments\Contracts;

use App\Models\PaymentMethod;

interface CustomerContract
{
    /**
     * Charge a customer.
     *
     * @param PaymentMethod $paymentMethod
     * @param int $amount
     * @return void
     */
    public function charge(PaymentMethod $paymentMethod, $amount);

    /**
     * Add a new card.
     *
     * @param string $token
     * @return void
     */
    public function addCard($token);
}