<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TechnologyFactory extends Factory
{
    protected $model = Technology::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'icon_path' => null,
        ];
    }
}
