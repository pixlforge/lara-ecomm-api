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
     * Cart property.
     *
     * @var Cart
     */
    protected $cart;
    
    /**
     * OrderController constructor.
     * 
     * @param Cart $cart 
     */
    public function __construct(Cart $cart)
    {
        $this->middleware(['auth:api']);

        $this->cart = $cart;
    }

    /**
     * Store a new order.
     *
     * @param OrderStoreRequest $request
     * @return void
     */
    public function store(OrderStoreRequest $request)
    {
        $order = $this->createOrder($request);

        //
    }

    /**
     * Create an order skeleton.
     *
     * @param Request $request
     * @return Order
     */
    protected function createOrder(Request $request)
    {
        return $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id']), [
                'subtotal' => $this->cart->subtotal()->getAmount()
            ])
        );
    }
}
