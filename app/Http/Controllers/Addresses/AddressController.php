<?php

namespace App\Http\Controllers\Addresses;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\AddressStoreRequest;
use App\Http\Resources\Addresses\AddressResource;

class AddressController extends Controller
{
    /**
     * AddressController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return all of the user's addresses.
     *
     * @param Request $request
     * @return AddressResource
     */
    public function index(Request $request)
    {
        return AddressResource::collection($request->user()->addresses);
    }

    /**
     * Store a new address.
     *
     * @param AddressStoreRequest $request
     * @return AddressResource
     */
    public function store(AddressStoreRequest $request)
    {
        $address = Address::make($request->only([
            'name', 'address_1', 'city', 'postal_code', 'country_id'
        ]));

        $request->user()->addresses()->save($address);

        return new AddressResource($address);
    }
}
