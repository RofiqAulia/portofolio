<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SkillType;
use App\Models\SkillCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillCategoryFactory extends Factory
{
    protected $model = SkillCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'type' => fake()->randomElement([SkillType::Hard, SkillType::Soft, SkillType::Analysis]),
            'sort_order' => 0,
        ];
    }
}
