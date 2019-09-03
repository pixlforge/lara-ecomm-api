<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;

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
    public function store(OrderStoreRequest $request)
    {
        dd('validation passed');
    }
}
