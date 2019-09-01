<?php

namespace App\Http\Controllers\Addresses;

use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShippingMethods\ShippingMethodResource;

class AddressShippingController extends Controller
{
    /**
     * AddressShippingController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    
    public function __invoke(Address $address)
    {
        $this->authorize('view', $address);
        
        return ShippingMethodResource::collection($address->country->shippingMethods);
    }
}
