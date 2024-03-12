<?php

namespace Tests\Unit\Repository\Eloquent;

use App\Models\Blog;
use App\Models\User;
use App\Repository\Eloquent\BlogRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_api_blog()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $blogData = [
            'title' => 'hi',
            'content' => 'hello',
            'excerpt' => 'welcome',
            'user_id' => 1,
        ];

        $response = $this->post('api/auth/createBlog', $blogData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('blogs', [
            'title' => $blogData['title'],
            'content' => $blogData['content'],
            'excerpt' => $blogData['excerpt'],
            'user_id' => $blogData['user_id'],
        ]);
    }

}
