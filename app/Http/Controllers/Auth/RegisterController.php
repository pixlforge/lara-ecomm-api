<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\PrivateUserResource;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return PrivateUserResource
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create($request->only([
            'name', 'email', 'password'
        ]));

        return new PrivateUserResource($user);
    }
}
