<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DocumentationType;
use App\Models\Documentation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Documentation>
 */
class DocumentationFactory extends Factory
{
    protected $model = Documentation::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'category' => fake()->randomElement([
                'ERD & Database',
                'UML & Flowchart',
                'Arsitektur Sistem',
                'Design Pattern & OOP',
            ]),
            'summary' => fake()->paragraph(2),
            'description' => fake()->paragraphs(3, true),
            'type' => DocumentationType::Image,
            'file_path' => 'documentations/files/sample.png',
            'preview_image_path' => null,
            'external_url' => null,
            'project_id' => null,
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => fake()->numberBetween(0, 10),
            'published_at' => now(),
        ];
    }

    public function document(): static
    {
        return $this->state(fn () => [
            'type' => DocumentationType::Document,
            'file_path' => 'documentations/files/sample.pdf',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }
}
