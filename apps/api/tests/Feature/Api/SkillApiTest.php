<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_skills_with_categories(): void
    {
        Skill::factory()->count(2)->create();

        $response = $this->getJson('/api/skills?include=category&include_counts=1');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data')
            ->assertNotNull($response->json('data.0.category'));
    }

    public function test_it_filters_skills_by_category_identifier(): void
    {
        $category = SkillCategory::factory()->create();
        $matching = Skill::factory()->create(['category_id' => $category->id]);
        Skill::factory()->create();

        $response = $this->getJson('/api/skills?category=' . $category->id);

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matching->id);
    }

    public function test_it_filters_skills_without_category(): void
    {
        $withoutCategory = Skill::factory()->withoutCategory()->create();
        Skill::factory()->create();

        $response = $this->getJson('/api/skills?category=none');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $withoutCategory->id);
    }

    public function test_it_shows_skill_with_projects(): void
    {
        $skill = Skill::factory()->create();
        $project = Project::factory()->published()->create(['title' => 'API Project']);
        $project->skills()->attach($skill);

        $response = $this->getJson('/api/skills/' . $skill->id . '?include=projects');

        $response->assertOk()
            ->assertJsonPath('data.name', $skill->name)
            ->assertJsonPath('data.projects.0.title', 'API Project');
    }
}
