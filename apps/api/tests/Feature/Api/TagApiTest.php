<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_tags_with_counts(): void
    {
        $tag = Tag::factory()->create(['name' => 'API']);
        $post = Post::factory()->published()->create();
        $project = Project::factory()->published()->create();
        $post->tags()->attach($tag);
        $project->tags()->attach($tag);

        Tag::factory()->create();

        $response = $this->getJson('/api/tags?include_counts=1');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        $payload = collect($response->json('data'))->firstWhere('slug', $tag->slug);
        $this->assertNotNull($payload);
        $this->assertSame(1, $payload['posts_count']);
        $this->assertSame(1, $payload['projects_count']);
    }

    public function test_it_filters_tags_by_content_type(): void
    {
        $postTag = Tag::factory()->create(['slug' => 'articles']);
        $projectTag = Tag::factory()->create(['slug' => 'apps']);
        Post::factory()->published()->create()->tags()->attach($postTag);
        Project::factory()->published()->create()->tags()->attach($projectTag);

        $response = $this->getJson('/api/tags?type=posts');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'articles');
    }

    public function test_it_shows_tag_with_related_entries(): void
    {
        $tag = Tag::factory()->create(['slug' => 'backend']);
        $post = Post::factory()->published()->create(['title' => 'Building APIs']);
        $project = Project::factory()->published()->create(['title' => 'API Platform']);
        $post->tags()->attach($tag);
        $project->tags()->attach($tag);

        $response = $this->getJson('/api/tags/backend?include=posts,projects');

        $response->assertOk()
            ->assertJsonPath('data.posts.0.title', 'Building APIs')
            ->assertJsonPath('data.projects.0.title', 'API Platform');
    }
}
