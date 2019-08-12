<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
    /** @test */
    public function it_requires_a_name()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_requires_a_name_of_at_least_3_characters()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => str_repeat('a', 2)
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_requires_a_name_of_at_most_255_characters()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => str_repeat('a', 256)
        ]);

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_requires_an_email()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_unique_email()
    {
        factory(User::class)->create([
            'email' => $email = 'john@example.com'
        ]);

        $response = $this->postJson(route('auth.register'), [
            'email' => $email
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        $response = $this->postJson(route('auth.register'), [
            'email' => 'invalid-email-address.com'
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_password()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_requires_a_password_of_at_least_8_characters()
    {
        $response = $this->postJson(route('auth.register'), [
            'password' => str_repeat('a', 7)
        ]);

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_registers_a_user()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => $name = 'John',
            'email' => $email = 'john@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email
        ]);
    }

    /** @test */
    public function it_returns_a_user_resource_on_registration()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => $name = 'John',
            'email' => $email = 'john@example.com',
            'password' => 'password'
        ]);

        $response->assertJsonFragment([
            'name' => $name,
            'email' => $email
        ]);
    }
}
