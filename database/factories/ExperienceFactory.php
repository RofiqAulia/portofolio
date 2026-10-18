<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExperienceType;
use App\Enums\ProjectStatus;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement([ExperienceType::Internship, ExperienceType::Organization]),
            'position' => fake()->jobTitle(),
            'organization_name' => fake()->company(),
            'location' => 'Jakarta, Indonesia',
            'start_date' => fake()->dateTimeBetween('-2 years', '-1 year'),
            'end_date' => fake()->dateTimeBetween('-11 months', 'now'),
            'is_current' => false,
            'summary' => fake()->text(200),
            'story' => fake()->paragraphs(3, true),
            'cover_path' => null,
            'status' => ProjectStatus::Published,
            'sort_order' => 0,
        ];
    }

    public function internship(): static
    {
        return $this->state(fn () => [
            'type' => ExperienceType::Internship,
        ]);
    }

    public function organization(): static
    {
        return $this->state(fn () => [
            'type' => ExperienceType::Organization,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => ProjectStatus::Draft,
        ]);
    }
}
