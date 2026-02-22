<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use Domain\Account\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase; 

    #[Test]
    public function login_returns_token_for_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/account/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'ok',
                     'data' => [
                         'user' => ['id', 'name', 'email'],
                         'token'
                     ]
                 ]);

        $response->assertJson([
            'ok' => true
        ]);
    }

    #[Test]
    function login_failed_for_invalid_credentials()
    {
        $response = $this->postJson("/api/v1/account/login", [
            'email' => 'daniel@example.com',
            'password' => 'wrongpass'
        ]);

        $response->assertJsonStructure([
            'ok',
            'message'
        ]);

        $response->assertJson([
            'ok' => false,
        ]);

    }
}