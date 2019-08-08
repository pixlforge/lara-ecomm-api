<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartUpdateRequest;

class CartController extends Controller
{
    /**
     * CartController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Add a product variation to the cart.
     *
     * @return void
     */
    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->add($request->products);
    }

    /**
     * Update the quantity of a product variation in the cart.
     *
     * @param ProductVariation $productVariation
     * @param CartUpdateRequest $request
     * @param Cart $cart
     * @return void
     */
    public function update(ProductVariation $productVariation, CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }
}
