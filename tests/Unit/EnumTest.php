<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\AttachmentType;
use App\Enums\ExperienceType;
use App\Enums\MediaType;
use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Enums\SkillType;
use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test_user_roles_have_expected_values(): void
    {
        $this->assertSame('admin', UserRole::Admin->value);
        $this->assertSame('editor', UserRole::Editor->value);
        $this->assertSame('Admin', UserRole::Admin->label());
    }

    public function test_skill_types_have_expected_values(): void
    {
        $this->assertSame('hard', SkillType::Hard->value);
        $this->assertSame('soft', SkillType::Soft->value);
        $this->assertSame('analysis', SkillType::Analysis->value);
    }

    public function test_project_statuses_have_expected_values(): void
    {
        $this->assertSame('draft', ProjectStatus::Draft->value);
        $this->assertSame('published', ProjectStatus::Published->value);
    }

    public function test_experience_types_have_expected_values(): void
    {
        $this->assertSame('internship', ExperienceType::Internship->value);
        $this->assertSame('organization', ExperienceType::Organization->value);
    }

    public function test_attachment_types_have_expected_values(): void
    {
        $this->assertSame('file', AttachmentType::File->value);
        $this->assertSame('link', AttachmentType::Link->value);
    }

    public function test_media_types_have_expected_values(): void
    {
        $this->assertSame('image', MediaType::Image->value);
        $this->assertSame('video', MediaType::Video->value);
    }

    public function test_message_statuses_have_expected_values(): void
    {
        $this->assertSame('new', MessageStatus::New->value);
        $this->assertSame('read', MessageStatus::Read->value);
        $this->assertSame('replied', MessageStatus::Replied->value);
        $this->assertSame('archived', MessageStatus::Archived->value);
    }
}
