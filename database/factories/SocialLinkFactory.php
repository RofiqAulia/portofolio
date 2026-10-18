<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profile;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'platform' => fake()->randomElement(['GitHub', 'LinkedIn', 'Instagram', 'Twitter']),
            'icon' => 'bi-link',
            'url' => fake()->url(),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
