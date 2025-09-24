<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_series_with_counts(): void
    {
        $series = Series::factory()->create(['title' => 'APIs']);
        Post::factory()->published()->create(['series_id' => $series->id]);
        Series::factory()->create();

        $response = $this->getJson('/api/series?include_counts=1');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        $payload = collect($response->json('data'))->firstWhere('slug', $series->slug);
        $this->assertSame(1, $payload['posts_count']);
    }

    public function test_it_supports_searching_series(): void
    {
        Series::factory()->create(['title' => 'API Series']);
        Series::factory()->create(['title' => 'Other']);

        $response = $this->getJson('/api/series?search=API');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_it_shows_series_with_posts(): void
    {
        $series = Series::factory()->create(['slug' => 'backend']);
        $post = Post::factory()->published()->create(['series_id' => $series->id, 'title' => 'REST APIs']);

        $response = $this->getJson('/api/series/backend?include=posts');

        $response->assertOk()
            ->assertJsonPath('data.title', $series->title)
            ->assertJsonPath('data.posts.0.title', 'REST APIs');
    }
}
