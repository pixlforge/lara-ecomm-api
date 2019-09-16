<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payments\Contracts\PaymentGatewayContract;
use App\Http\Resources\PaymentMethods\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    /**
     * The payment gateway property.
     *
     * @var PaymentGatewayContract $paymentGateway
     */
    protected $paymentGateway;
    
    /**
     * PaymentMethodController constructor.
     */
    public function __construct(PaymentGatewayContract $paymentGateway)
    {
        $this->middleware(['auth:api']);

        $this->paymentGateway = $paymentGateway;
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

    /**
     * Store a new payment method.
     *
     * @return void
     */
    public function store(Request $request)
    {
        $card = $this->paymentGateway->withUser($request->user())
            ->getOrCreateCustomer()
            ->addCard($request->token);
    }
}
