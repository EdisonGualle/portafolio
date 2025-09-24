<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\ProjectBlock;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_published_projects_by_default(): void
    {
        $visible = Project::factory()->published()->create(['title' => 'Portfolio']);
        Project::factory()->create(['title' => 'Draft Project']);
        Project::factory()->published()->create(['published_at' => now()->addDays(2)]);

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $visible->slug);
    }

    public function test_it_filters_projects_by_skill_identifier(): void
    {
        $skill = Skill::factory()->create(['name' => 'Laravel']);
        $matching = Project::factory()->published()->create();
        $matching->skills()->attach($skill);

        Project::factory()->published()->create();

        $response = $this->getJson('/api/projects?skill=' . $skill->id);

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matching->id);
    }

    public function test_it_filters_projects_by_tag_slug(): void
    {
        $tag = Tag::factory()->create(['slug' => 'fullstack']);
        $matching = Project::factory()->published()->create();
        $matching->tags()->attach($tag);

        Project::factory()->published()->create();

        $response = $this->getJson('/api/projects?tag=fullstack');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matching->id);
    }

    public function test_it_supports_project_search(): void
    {
        $target = Project::factory()->published()->create(['summary' => 'Building a public API']);
        Project::factory()->published()->create(['summary' => 'Another project']);

        $response = $this->getJson('/api/projects?search=public API');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $target->id);
    }

    public function test_it_shows_project_with_related_information(): void
    {
        $project = Project::factory()->published()->create(['slug' => 'awesome-project']);
        $skill = Skill::factory()->create(['name' => 'PHP']);
        $tag = Tag::factory()->create(['name' => 'Backend']);
        $project->skills()->attach($skill);
        $project->tags()->attach($tag);
        ProjectBlock::factory()->for($project)->create(['type' => 'text', 'order_index' => 1]);

        $response = $this->getJson('/api/projects/awesome-project');

        $response->assertOk()
            ->assertJsonPath('data.title', $project->title)
            ->assertJsonPath('data.skills.0.name', 'PHP')
            ->assertJsonPath('data.tags.0.name', 'Backend')
            ->assertJsonPath('data.blocks.0.type', 'text');
    }

    public function test_it_returns_404_for_draft_project_without_status_override(): void
    {
        $project = Project::factory()->create(['slug' => 'secret-project']);

        $this->getJson('/api/projects/secret-project')->assertNotFound();

        $this->getJson('/api/projects/secret-project?status=draft')
            ->assertOk()
            ->assertJsonPath('data.slug', 'secret-project');
    }
}
