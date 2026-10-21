<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    protected $model = Certification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'category' => fake()->randomElement([
                'Hak Cipta (HKI)',
                'Sertifikasi Magang',
                'Penghargaan Kinerja',
                'Kepemimpinan',
                'Predikat Akademik',
                'Sertifikasi Profesi',
            ]),
            'issuer' => fake()->company(),
            'issued_year' => (string) fake()->numberBetween(2022, 2026),
            'status_label' => fake()->randomElement(['Terdaftar Resmi', 'Lulus Terverifikasi', 'Best Intern', 'Cumlaude', 'Terverifikasi']),
            'credential_id' => 'ID-'.fake()->numerify('#####'),
            'credential_url' => fake()->optional()->url(),
            'action_url' => null,
            'action_label' => null,
            'description' => fake()->paragraph(),
            'images' => [],
            'files' => [],
            'is_published' => true,
            'is_featured' => fake()->boolean(20),
            'sort_order' => fake()->numberBetween(0, 10),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
