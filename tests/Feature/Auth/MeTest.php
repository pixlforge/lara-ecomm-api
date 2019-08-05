<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class MeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fails_if_unauthenticated()
    {
        $response = $this->getJson(route('auth.me'));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_user_details()
    {
        $user = factory(User::class)->create();

        $response = $this->getJsonAs($user, route('auth.me'));

        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
