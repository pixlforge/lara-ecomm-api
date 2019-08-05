<?php

namespace Tests\Unit\Models\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_hashes_the_password_when_creating()
    {
        $user = factory(User::class)->create([
            'password' => $password = 'password'
        ]);

        $this->assertNotEquals($password, $user->password);
    }
}
