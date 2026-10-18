<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MessageStatus;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'status' => MessageStatus::New,
            'ip_address' => fake()->ipv4(),
            'read_at' => null,
        ];
    }
}
