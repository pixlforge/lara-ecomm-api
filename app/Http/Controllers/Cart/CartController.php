<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
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
     * Get the user's cart.
     *
     * @param Request $request
     * @return CartResource
     */
    public function index(Request $request, Cart $cart)
    {
        $cart->sync();

        $request->user()->load([
            'cart.product.variations.stock', 'cart.product.categories', 'cart.stock', 'cart.type'
        ]);

        return (CartResource::make($request->user()))
            ->additional([
                'meta' => $this->meta($cart, $request)
            ]);
    }
    
    /**
     * Add a product variation to the cart.
     *
     * @param CartStoreRequest $request
     * @param Cart $cart
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

    /**
     * Remove a product variation from the cart.
     *
     * @param ProductVariation $productVariation
     * @param Cart $cart
     * @return void
     */
    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation);
    }

    /**
     * Get the cart's additional information.
     *
     * @param Cart $cart
     * @param Request $request
     * @return array
     */
    protected function meta(Cart $cart, Request $request)
    {
        return [
            'isEmpty' => $cart->isEmpty(),
            'subtotal' => [
                'detailed' => $cart->subtotal()->detailed(),
                'formatted' => $cart->subtotal()->formatted()
            ],
            'total' => [
                'detailed' => $cart->withShipping($request->shipping_method_id)->total()->detailed(),
                'formatted' => $cart->withShipping($request->shipping_method_id)->total()->formatted()
            ],
            'hasChanged' => $cart->hasChanged()
        ];
    }
}
