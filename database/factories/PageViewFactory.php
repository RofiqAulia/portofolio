<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageViewFactory extends Factory
{
    protected $model = PageView::class;

    public function definition(): array
    {
        return [
            'page' => '/',
            'project_id' => null,
            'visitor_hash' => fake()->sha256(),
            'referrer' => fake()->url(),
            'user_agent' => fake()->userAgent(),
            'created_at' => now(),
        ];
    }
}
