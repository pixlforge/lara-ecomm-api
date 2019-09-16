<?php

namespace App\Providers;

use Stripe\Stripe;
use Illuminate\Support\ServiceProvider;
use App\Payments\Stripe\StripePaymentGateway;
use App\Payments\Contracts\PaymentGatewayContract;

class PaymentGatewayProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentGatewayContract::class, function () {
            return new StripePaymentGateway();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
}
