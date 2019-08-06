<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\PrivateUserResource;

class MeController extends Controller
{
    /**
     * MeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Return the authenticated user resource.
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        if (!$request->user()) {
            return;
        }
        
        return new PrivateUserResource($request->user());
    }
}
