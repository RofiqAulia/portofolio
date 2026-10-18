<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'skill_category_id' => SkillCategory::factory(),
            'name' => fake()->word(),
            'level' => fake()->numberBetween(60, 95),
            'level_label' => 'Advanced',
            'sort_order' => 0,
            'is_visible' => true,
        ];
    }
}
