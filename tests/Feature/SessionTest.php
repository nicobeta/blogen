<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->prepareForTests();
    }

    private function prepareForTests()
    {
        $response = $this->json('POST', 'api/auth/signup', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->user = json_decode($response->getContent())->data;
    }

    public function test_a_user_can_login()
    {
        $response = $this->json('POST', 'api/auth/login', [
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

    public function test_a_user_can_check_his_session()
    {
        $response = $this->json('GET', 'api/auth/whoami?token='. $this->user->token);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Test',
                    'email' => 'test@example.com'
                ],
                'success' => true,
            ]);
    }
}
