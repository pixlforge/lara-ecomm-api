<?php

namespace Tests;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Get request as an authenticated user.
     *
     * @param JWTSubject $user
     * @param $endpoint
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function getJsonAs(JWTSubject $user, $endpoint, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json('GET', $endpoint, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]));
    }

    /**
     * Post request as an authenticated user.
     *
     * @param JWTSubject $user
     * @param $endpoint
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function postJsonAs(JWTSubject $user, $endpoint, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json('POST', $endpoint, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]));
    }

    /**
     * Patch request as an authenticated user.
     *
     * @param JWTSubject $user
     * @param $endpoint
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function patchJsonAs(JWTSubject $user, $endpoint, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json('PATCH', $endpoint, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]));
    }

    /**
     * Delete request as an authenticated user.
     *
     * @param JWTSubject $user
     * @param $endpoint
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function deleteJsonAs(JWTSubject $user, $endpoint, $data = [], $headers = [])
    {
        $token = auth()->tokenById($user->id);

        return $this->json('DELETE', $endpoint, $data, array_merge($headers, [
            'Authorization' => 'Bearer ' . $token
        ]));
    }
}
