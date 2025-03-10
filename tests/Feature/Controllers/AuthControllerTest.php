<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    #[Test]
    public function a_user_can_login()
    {
        $user = User::factory()->create();

        $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }

    #[Test]
    public function an_unregistered_user_cannot_login()
    {
        $user = User::factory()->create();

        $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertUnauthorized();
    }

    #[Test]
    public function a_user_can_register()
    {
        $this->postJson('api/auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ])
            ->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }
}
