<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
            'category_id' => SkillCategory::factory(),
            'level' => $this->faker->numberBetween(40, 100),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function withoutCategory(): self
    {
        return $this->state(fn () => [
            'category_id' => null,
        ]);
    }
}
