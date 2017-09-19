<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CommentsTest extends TestCase
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

    private function createComment()
    {
        $this->post = factory(Post::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/posts/' . $this->post->id . '/comments', [
            'body' => 'This is my comment'
        ]);

        $this->comment = json_decode($response->getContent())->data;

        return $response;
    }

    public function test_a_registered_user_can_comment_a_post()
    {
        $response = $this->createComment();

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'body' => 'This is my comment',
                    'post_id' => $this->post->id,
                    'user_id' => $this->user->id
                ],
                'success' => true,
            ]);
    }

    public function test_a_comment_has_required_fields()
    {
        $post = factory(Post::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('POST', 'api/posts/' . $post->id . '/comments');

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 'invalid_fields'
            ]);
    }

    public function test_a_comment_can_be_read()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->json('GET', 'api/posts/' . $comment->post_id . '/comments/' . $comment->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'post_id' => $comment->post_id
                ]
            ]);
    }

    public function test_a_comment_owner_can_edit_his_comment()
    {
        $this->createComment();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('PUT', 'api/posts/' . $this->post->id . '/comments/' . $this->comment->id, [
            'body' => 'This is my updated comment'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'body' => 'This is my updated comment'
                ]
            ]);
    }

    public function test_a_comment_owner_can_delete_his_comment()
    {
        $this->createComment();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('DELETE', 'api/posts/' . $this->post->id . '/comments/' . $this->comment->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function test_a_guest_can_not_comment()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('POST', 'api/posts/' . $post->id . '/comments', [
            'body' => 'Some comment'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_an_unexisting_comment_can_not_be_reached()
    {
        $response = $this->json('GET', 'api/posts/99/comment/99');

        $response->assertStatus(404);

        $response = $this->json('PUT', 'api/posts/99/comment/99');

        $response->assertStatus(404);

        $response = $this->json('DELETE', 'api/posts/99/comment/99');

        $response->assertStatus(404);
    }

    public function test_a_guest_can_not_edit_comments()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->json('PUT', 'api/posts/' . $comment->post_id . '/comments/' . $comment->id, [
            'body' => 'Some comment'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'token_not_provided'
            ]);
    }

    public function test_a_guest_can_not_delete_comments()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->json('DELETE', 'api/posts/' . $comment->post_id . '/comments/' . $comment->id);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'token_not_provided'
            ]);
    }

    public function test_a_user_can_not_edit_a_comment_he_does_not_own()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('PUT', 'api/posts/' . $comment->post_id . '/comments/' . $comment->id, [
            'title' => 'Updated title'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'user_unauthorized'
            ]);
    }

    public function test_a_user_can_not_delete_a_comment_he_does_not_own()
    {
        $comment = factory(Comment::class)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->token
        ])->json('DELETE', 'api/posts/' . $comment->post_id . '/comments/' . $comment->id);

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'code' => 'user_unauthorized'
            ]);
    }
}
