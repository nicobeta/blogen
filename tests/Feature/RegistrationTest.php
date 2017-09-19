<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $response = $this->json('POST', 'api/auth/signup', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Test',
                    'email' => 'test@example.com'
                ],
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['token']
            ]);
    }
}
