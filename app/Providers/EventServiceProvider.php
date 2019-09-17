<?php

namespace App\Providers;

use App\Events\Orders\OrderCreated;
use App\Events\Orders\OrderPaymentFailed;
use App\Listeners\Orders\EmptyCart;
use App\Listeners\Orders\MarkOrderPaymentFailed;
use Illuminate\Auth\Events\Registered;
use App\Listeners\Orders\ProcessPayment;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderCreated::class => [
            ProcessPayment::class,
            EmptyCart::class,
        ],
        OrderPaymentFailed::class => [
            MarkOrderPaymentFailed::class
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
