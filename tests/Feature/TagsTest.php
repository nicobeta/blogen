<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Category;
use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_tags_can_be_listed()
    {
        $post = factory(Post::class, 10)->create();

        $response = $this->json('GET', 'api/tags');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => []
            ]);

        $tags = json_decode($response->getContent())->data;
        $this->assertInternalType('array', $tags);
    }
}
