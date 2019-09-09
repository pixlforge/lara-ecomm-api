<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;

class Sync
{
    /**
     * The cart property.
     *
     * @var Cart $cart
     */
    protected $cart;
    
    /**
     * Sync constructor.
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
        $this->cart->sync();

        if ($this->cart->hasChanged()) {
            return response([
                'message' => 'Oh no! It looks like some items in your cart have changed. Please, review the contents of your cart before placing your order.'
            ], 409);
        }
        
        return $next($request);
    }
}
