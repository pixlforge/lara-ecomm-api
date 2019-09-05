<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Models\Order;
use App\Models\ProductVariation;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Store a new order.
     *
     * @param OrderStoreRequest $request
     * @return void
     */
    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $products = $cart->products()->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product->pivot->quantity
            ];
        })->toArray();

        $order->products()->sync($products);

        // TODO: Return the order with a resource
    }

    /**
     * Create an order skeleton.
     *
     * @param Request $request
     * @param Cart $cart
     * @return Order
     */
    protected function createOrder(Request $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id']), [
                'subtotal' => $cart->subtotal()->getAmount()
            ])
        );
    }
}
