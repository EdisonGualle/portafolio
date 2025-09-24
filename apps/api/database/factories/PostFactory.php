<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'excerpt' => $this->faker->paragraph(),
            'body_md' => $this->faker->paragraphs(3, true),
            'status' => 'draft',
            'published_at' => null,
            'series_id' => null,
            'og_image_url' => $this->faker->imageUrl(1200, 630, 'business', true),
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now()->subDays($this->faker->numberBetween(0, 30)),
        ]);
    }

    public function withSeries(): self
    {
        return $this->state(fn () => [
            'series_id' => Series::factory(),
        ]);
    }
}
