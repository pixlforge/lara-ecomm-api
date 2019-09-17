<?php

namespace App\Payments\Stripe;

use Exception;
use App\Models\PaymentMethod;
use Stripe\Charge as BaseCharge;
use Stripe\Customer as BaseCustomer;
use App\Exceptions\PaymentFailedException;
use App\Payments\Contracts\CustomerContract;
use App\Payments\Contracts\PaymentGatewayContract;

class StripeCustomer implements CustomerContract
{
    /**
     * The payment gateway property.
     *
     * @var PaymentGatewayContract $paymentGateway
     */
    protected $paymentGateway;

    /**
     * The customer property.
     *
     * @var BaseCustomer $customer
     */
    protected $customer;

    /**
     * StripeCustomer constructor.
     *
     * @param PaymentGatewayContract $paymentGateway
     */
    public function __construct(PaymentGatewayContract $paymentGateway, BaseCustomer $customer)
    {
        $this->paymentGateway = $paymentGateway;
        $this->customer = $customer;
    }
    
    /**
     * Charge a customer.
     *
     * @param PaymentMethod $paymentMethod
     * @param int $amount
     * @return void
     */
    public function charge(PaymentMethod $paymentMethod, $amount)
    {
        try {
            throw new PaymentFailedException(Exception);

            BaseCharge::create([
                'currency' => 'chf',
                'amount' => $amount,
                'customer' => $this->customer->id,
                'source' => $paymentMethod->provider_id
            ]);
        } catch (Exception $e) {
            throw new PaymentFailedException($e);
        }
    }

    /**
     * Add a new card.
     *
     * @param string $token
     * @return void
     */
    public function addCard($token)
    {
        $card = $this->customer->sources->create([
            'source' => $token
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->paymentGateway->getUser()->paymentMethods()->create([
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'provider_id' => $card->id,
            'default' => true
        ]);
    }

    /**
     * Get the Stripe Customer id.
     *
     * @return string
     */
    public function id()
    {
        return $this->customer->id;
    }
}