<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AttachmentType;
use App\Models\Experience;
use App\Models\ExperienceAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceAttachmentFactory extends Factory
{
    protected $model = ExperienceAttachment::class;

    public function definition(): array
    {
        return [
            'experience_id' => Experience::factory(),
            'type' => AttachmentType::Link,
            'title' => fake()->words(3, true),
            'file_path' => null,
            'original_name' => null,
            'mime_type' => null,
            'file_size' => null,
            'url' => fake()->url(),
            'sort_order' => 0,
            'is_visible' => true,
        ];
    }

    public function file(): static
    {
        return $this->state(fn () => [
            'type' => AttachmentType::File,
            'file_path' => 'attachments/sample.pdf',
            'original_name' => 'certificate.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 102400,
            'url' => null,
        ]);
    }
}
