<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'headline' => 'Senior Full Stack Web Developer & Problem Solver',
            'tagline' => 'Crafting robust and elegant digital solutions with Laravel & Modern Tech',
            'bio' => fake()->paragraph(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => 'Jakarta, Indonesia',
            'quote_text' => 'Simplicity is the soul of efficiency.',
            'quote_author' => 'Austin Freeman',
            'is_open_to_work' => true,
        ];
    }
}
