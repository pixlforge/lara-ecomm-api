<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;

class ResponseWhenEmpty
{
    /**
     * The cart property,
     *
     * @var Cart $cart
     */
    protected $cart;

    /**
     * ResponseWhenEmpty constructor.
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->cart->isEmpty()) {
            return response([
                'message' => 'Your cart is currently empty.'
            ], 400);
        }
        
        return $next($request);
    }
}
