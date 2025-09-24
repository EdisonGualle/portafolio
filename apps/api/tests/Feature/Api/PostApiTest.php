<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Series;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_published_posts_by_default(): void
    {
        $published = Post::factory()->published()->create(['title' => 'Visible Post']);
        Post::factory()->create(['title' => 'Hidden Draft']);
        Post::factory()->published()->create(['published_at' => now()->addDay()]);

        $response = $this->getJson('/api/posts');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $published->slug);
    }

    public function test_it_filters_posts_by_tag_slug(): void
    {
        $tag = Tag::factory()->create(['slug' => 'laravel']);
        $matching = Post::factory()->published()->create();
        $matching->tags()->attach($tag);

        Post::factory()->published()->create();

        $response = $this->getJson('/api/posts?tag=laravel');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matching->id);
    }

    public function test_it_allows_status_filter_for_drafts(): void
    {
        $draft = Post::factory()->create(['title' => 'Drafted']);
        Post::factory()->published()->create();

        $response = $this->getJson('/api/posts?status=draft');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $draft->slug);
    }

    public function test_it_supports_searching_content(): void
    {
        $target = Post::factory()->published()->create(['title' => 'API Tips and Tricks']);
        Post::factory()->published()->create(['title' => 'Another Article']);

        $response = $this->getJson('/api/posts?search=Tips');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $target->id);
    }

    public function test_it_shows_single_post_when_available(): void
    {
        $series = Series::factory()->create(['title' => 'Backend']);
        $tag = Tag::factory()->create(['name' => 'PHP']);
        $post = Post::factory()->published()->create([
            'slug' => 'laravel-api',
            'series_id' => $series->id,
        ]);
        $post->tags()->attach($tag);

        $response = $this->getJson('/api/posts/laravel-api');

        $response->assertOk()
            ->assertJsonPath('data.title', $post->title)
            ->assertJsonPath('data.series.title', 'Backend')
            ->assertJsonPath('data.tags.0.name', 'PHP');
    }

    public function test_it_returns_404_for_draft_post_without_status_override(): void
    {
        $post = Post::factory()->create(['slug' => 'hidden-post']);

        $this->getJson('/api/posts/hidden-post')->assertNotFound();

        $this->getJson('/api/posts/hidden-post?status=draft')
            ->assertOk()
            ->assertJsonPath('data.slug', 'hidden-post');
    }
}
