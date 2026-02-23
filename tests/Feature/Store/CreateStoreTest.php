<?php

namespace Tests\Feature\Store;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class CreateStoreTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    #[Test]
    function create_new_store_successfully()
    {
        $this->actingAsUser();

        $response = $this->postJson('/api/v1/stores', [
            'name' => 'Test Store',
            'description' => 'Test description',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'ok' => true,
            'data' => [
                'store' => [
                    'name' => 'Test Store',
                    'description' => 'Test description',
                    'user_id' => $this->user->id,
                ]
            ]
        ]);

        // Checking public_id (uuid) has been generated automatically ?
        $publicId = $response->json('data')['store']['public_id'] ?? null;
        $this->assertNotNull($publicId, message: "Sote's public id is not generated.");

    }

    #[Test]
    function create_store_requires_name()
    {
        $this->actingAsUser();

        $response = $this->postJson("api/v1/stores");

        $response->assertStatus(422); // validation error
        $response->assertJsonValidationErrorFor('name');
    }

   
    #[Test]
    function unauthenticated_user_cannot_create_store()
    {
        auth()->logout();

        $response = $this->postJson('/api/v1/stores', [
            'name' => 'Hack Store'
        ]);

        $response->assertStatus(401); // Unauthorized user
    }
}
