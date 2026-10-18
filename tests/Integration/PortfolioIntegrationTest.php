<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Actions\PublishProject;
use App\Actions\ReplaceExperienceAttachment;
use App\Contracts\AttachmentStorage;
use App\Enums\AttachmentType;
use App\Models\Experience;
use App\Models\ExperienceAttachment;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_publishing_draft_project_makes_it_available_on_landing_page_and_api(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $project = Project::factory()->draft()->create([
            'title' => 'Project Dari Draft Ke Publish',
            'slug' => 'project-draft-ke-publish',
        ]);

        // Verify initially absent from landing page & API
        $this->get(route('portfolio.home'))->assertDontSee('Project Dari Draft Ke Publish');
        $this->getJson('/api/v1/projects/'.$project->slug)->assertStatus(404);

        // Act: publish via PublishProject Action
        $action = app(PublishProject::class);
        $action->execute($project);

        // Assert: now visible on landing page & API
        $this->get(route('portfolio.home'))->assertSee('Project Dari Draft Ke Publish');
        $this->getJson('/api/v1/projects/'.$project->slug)
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'Project Dari Draft Ke Publish']);
    }

    public function test_replacing_or_deleting_attachment_cleans_up_files_in_storage(): void
    {
        // Arrange
        Storage::fake('public');
        $storage = app(AttachmentStorage::class);

        $fakeFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
        $storedPath = $storage->store($fakeFile, 'attachments');

        $this->assertTrue(Storage::disk('public')->exists($storedPath));

        $experience = Experience::factory()->create();
        $attachment = ExperienceAttachment::create([
            'experience_id' => $experience->id,
            'type' => AttachmentType::File,
            'title' => 'Sertifikat Asli',
            'file_path' => $storedPath,
            'original_name' => 'document.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'is_visible' => true,
        ]);

        // Act 1: Replace file with link using ReplaceExperienceAttachment Action
        $replaceAction = app(ReplaceExperienceAttachment::class);
        $replaceAction->execute($attachment, [
            'type' => AttachmentType::Link,
            'title' => 'Sertifikat Diganti Link',
            'url' => 'https://verify.example.com/cert/123',
        ]);

        // Assert: Old file is deleted from storage
        $this->assertFalse(Storage::disk('public')->exists($storedPath));
        $this->assertSame(AttachmentType::Link, $attachment->fresh()->type);

        // Act 2: Put a file back and delete the attachment record completely
        $newFile = UploadedFile::fake()->create('final.pdf', 50, 'application/pdf');
        $newStoredPath = $storage->store($newFile, 'attachments');
        $attachment->update([
            'type' => AttachmentType::File,
            'file_path' => $newStoredPath,
            'url' => null,
        ]);
        $this->assertTrue(Storage::disk('public')->exists($newStoredPath));

        // Delete attachment record -> Observer should clean up storage
        $attachment->delete();
        $this->assertFalse(Storage::disk('public')->exists($newStoredPath));
    }
}
