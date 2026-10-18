<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use App\Models\ExperienceHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceHighlightFactory extends Factory
{
    protected $model = ExperienceHighlight::class;

    public function definition(): array
    {
        return [
            'experience_id' => Experience::factory(),
            'content' => fake()->sentence(10),
            'sort_order' => 0,
        ];
    }
}
