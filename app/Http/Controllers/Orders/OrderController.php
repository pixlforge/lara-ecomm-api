<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\Orders\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Requests\Orders\OrderStoreRequest;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware(['cart.sync', 'cart.empty'])->only(['store']);
    }

    /**
     * Get the user's orders.
     *
     * @param Request $request
     * @return OrderResource
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with([
                'products.stock', 'products.type', 'products.product.variations.stock', 'address.country', 'shippingMethod'
            ])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Store a new order.
     *
     * @param OrderStoreRequest $request
     * @param Cart $cart
     * @return OrderResource
     */
    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSyncing());

        OrderCreated::dispatch($order);

        return OrderResource::make($order);
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
            array_merge($request->only(['address_id', 'shipping_method_id', 'payment_method_id']), [
                'subtotal' => $cart->subtotal()->getAmount()
            ])
        );
    }
}
