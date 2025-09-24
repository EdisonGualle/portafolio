<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'summary' => $this->faker->paragraph(),
            'body_md' => $this->faker->paragraphs(4, true),
            'repo_url' => $this->faker->url(),
            'demo_url' => $this->faker->url(),
            'featured' => $this->faker->boolean(20),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'status' => 'draft',
            'published_at' => null,
            'og_image_url' => $this->faker->imageUrl(1200, 630, 'technics', true),
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now()->subDays($this->faker->numberBetween(0, 60)),
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn () => [
            'featured' => true,
        ]);
    }
}
