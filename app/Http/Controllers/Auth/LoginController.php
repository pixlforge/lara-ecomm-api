<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Users\PrivateUserResource;

class LoginController extends Controller
{
    /**
     * Attempt to log a user in.
     *
     * @param LoginRequest $request
     * @return PrivateUserResource|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function __invoke(LoginRequest $request)
    {
        $token = auth()->attempt($request->only('email', 'password'));

        if (!$token) {
            return response()->json([
                'errors' => [
                    'email' => ['Could not sign you in with the credentials provided.']
                ]
            ], 422);
        }

        return (new PrivateUserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token
                ]
            ]);
    }
}
