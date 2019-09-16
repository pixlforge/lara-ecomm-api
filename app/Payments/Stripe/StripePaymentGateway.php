<?php

namespace App\Payments\Stripe;

use App\Models\User;
use Stripe\Customer as BaseCustomer;
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
     * Get the the user property.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get or create a new Customer resource.
     *
     * @return StripeCustomer
     */
    public function getOrCreateCustomer()
    {
        if ($this->user->gateway_customer_id) {
            return $this->getStripeCustomer();
        }

        $customer = new StripeCustomer($this, $this->createStripeCustomer());

        $this->user->update([
            'gateway_customer_id' => $customer->id()
        ]);

        return $customer;
    }

    /**
     * Get an existing Customer from Stripe.
     *
     * @return StripeCustomer
     */
    protected function getStripeCustomer()
    {
        return new StripeCustomer($this, BaseCustomer::retrieve($this->user->gateway_customer_id));
    }

    /**
     * Create a new Customer resource over on Stripe.
     *
     * @return BaseCustomer
     */
    protected function createStripeCustomer()
    {
        return BaseCustomer::create([
            'email' => $this->user->email
        ]);
    }
}