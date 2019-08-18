<?php

namespace App\Http\Controllers\Addresses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function index(Request $request)
    {
        return AddressResource::collection($request->user()->addresses);
    }
}
