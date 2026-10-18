<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MediaType;
use App\Models\Media;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'mediable_type' => Project::class,
            'mediable_id' => Project::factory(),
            'type' => MediaType::Image,
            'path' => 'media/sample.jpg',
            'caption' => fake()->sentence(4),
            'sort_order' => 0,
        ];
    }
}
