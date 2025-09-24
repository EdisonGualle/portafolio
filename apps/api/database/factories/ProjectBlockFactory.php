<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectBlock>
 */
class ProjectBlockFactory extends Factory
{
    protected $model = ProjectBlock::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'type' => $this->faker->randomElement(['text', 'image', 'highlight']),
            'data_json' => [
                'content' => $this->faker->paragraph(),
            ],
            'order_index' => $this->faker->numberBetween(1, 5),
        ];
    }
}
