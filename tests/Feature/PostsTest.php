<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->prepareForTests();
    }

    private function prepareForTests()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', 'api/auth/signup', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->user = json_decode($response->getContent())->data;
    }

    private function createPost()
    {
        $post = factory(Post::class)->make();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/posts', [
            'title' => $post->title,
            'body' => $post->body
        ]);

        $this->post = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_a_registered_user_can_create_a_post()
    {
        $response = $this->createPost();

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => $this->post->title,
                    'body' => $this->post->body
                ],
                'success' => true,
            ]);
    }

    public function test_a_post_has_required_fields()
    {
        $post = factory(Post::class)->make();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/posts', [
            'title' => $post->title
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ])
            ->assertJsonStructure([
                'errors' => ['body']
            ]);
    }

    public function test_a_post_can_have_a_category()
    {
        $post = factory(Post::class)->make();
        $category = factory(Category::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/posts', [
            'title' => $post->title,
            'body' => $post->body,
            'category_id' => $category->id
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => $post->title,
                    'body' => $post->body,
                    'category_id' => $category->id
                ]
            ]);
    }

    public function test_a_post_can_be_read()
    {
        $this->createPost();

        $response = $this->json('GET', 'api/posts/' . $this->post->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => $this->post->title
                ]
            ]);
    }

    public function test_a_post_owner_can_edit_his_post()
    {
        $this->createPost();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('PUT', 'api/posts/' . $this->post->id, [
            'title' => 'Updated title'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Updated title'
                ]
            ]);
    }

    public function test_a_post_owner_can_delete_his_post()
    {
        $this->createPost();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('DELETE', 'api/posts/' . $this->post->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_guest_can_not_create_a_post()
    {
        $post = factory(Post::class)->make();

        $response = $this->json('POST', 'api/posts', [
            'title' => $post->title,
            'body' => $post->body
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_unexisting_post_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/posts/5');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/posts/5');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/posts/5');

        $response->assertStatus(404);
    }

    public function test_a_guest_can_not_edit_a_post()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('PUT', 'api/posts/' . $post->id, [
            'title' => 'Updated title'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'token_not_provided'
            ]);
    }

    public function test_a_guest_can_not_delete_a_post()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('DELETE', 'api/posts/' . $post->id);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'token_not_provided'
            ]);
    }

    public function test_a_user_can_not_edit_a_post_he_does_not_own()
    {
        $post = factory(Post::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('PUT', 'api/posts/' . $post->id, [
            'title' => 'Updated title'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'user_unauthorized'
            ]);
    }

    public function test_a_user_can_not_delete_a_post_he_does_not_own()
    {
        $post = factory(Post::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('DELETE', 'api/posts/' . $post->id);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'user_unauthorized'
            ]);
    }
}
