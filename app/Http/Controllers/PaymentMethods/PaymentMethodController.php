<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethods\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    /**
     * PaymentMethodController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    
    /**
     * Get a collection of the user's payment methods.
     *
     * @param Request $request
     * @return PaymentMethodResource
     */
    public function index(Request $request)
    {
        return PaymentMethodResource::collection($request->user()->paymentMethods);
    }
}
