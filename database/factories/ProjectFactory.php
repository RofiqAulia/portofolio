<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'summary' => fake()->text(200),
            'description' => fake()->paragraphs(3, true),
            'role' => 'Lead Developer',
            'thumbnail_path' => null,
            'demo_url' => fake()->url(),
            'repo_url' => fake()->url(),
            'started_at' => fake()->dateTimeBetween('-1 year', '-6 months'),
            'finished_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'status' => ProjectStatus::Published,
            'is_featured' => fake()->boolean(40),
            'sort_order' => 0,
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => ProjectStatus::Draft,
            'published_at' => null,
        ]);
    }
}
