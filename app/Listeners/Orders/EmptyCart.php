<?php

namespace App\Listeners\Orders;

use App\Cart\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmptyCart implements ShouldQueue
{
    /**
     * The cart.
     *
     * @var Cart
     */
    protected $cart;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->cart->empty();
    }
}
