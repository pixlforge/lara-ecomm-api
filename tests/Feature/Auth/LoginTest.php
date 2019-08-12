<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /** @test */
    public function it_requires_an_email()
    {
        $response = $this->postJson(route('auth.login'));

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_password()
    {
        $response = $this->postJson(route('auth.login'));

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_fails_if_credentials_do_not_match()
    {
        factory(User::class)->create([
            'email' => $email = 'john@example.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $email,
            'password' => 'something-else'
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_returns_a_token_if_credentials_do_match()
    {
        factory(User::class)->create([
            'email' => $email = 'john@example.com',
            'password' => $password = 'password'
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
    }

    /** @test */
    public function it_returns_a_user_if_credentials_do_match()
    {
        factory(User::class)->create([
            'email' => $email = 'john@example.com',
            'password' => $password = 'password'
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertJsonFragment([
            'email' => $email
        ]);
    }
}
