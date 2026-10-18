<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DetailPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_published_project_detail(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $project = Project::factory()->create([
            'title' => 'Sistem Logistik Canggih',
            'slug' => 'sistem-logistik-canggih',
            'status' => ProjectStatus::Published,
        ]);

        // Act
        $response = $this->get('/proyek/'.$project->slug);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Sistem Logistik Canggih');
    }

    public function test_user_cannot_view_draft_project_detail_returns_404(): void
    {
        // Arrange
        $project = Project::factory()->draft()->create([
            'slug' => 'draft-project-hidden',
        ]);

        // Act
        $response = $this->get('/proyek/'.$project->slug);

        // Assert
        $response->assertStatus(404);
    }

    public function test_user_can_view_published_experience_detail(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $experience = Experience::factory()->internship()->create([
            'position' => 'DevOps Intern',
            'organization_name' => 'PT Cloud Nusantara',
            'status' => ProjectStatus::Published,
        ]);

        // Act
        $response = $this->get('/pengalaman/'.$experience->id);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('DevOps Intern');
        $response->assertSee('PT Cloud Nusantara');
    }

    public function test_user_can_download_published_project_thumbnail(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('projects/thumbnails/demo.png', 'fake image bytes');

        $project = Project::factory()->create([
            'title' => 'Equipment Monitoring System',
            'slug' => 'equipment-monitoring-system',
            'thumbnail_path' => 'projects/thumbnails/demo.png',
            'status' => ProjectStatus::Published,
        ]);

        // Act
        $response = $this->get('/proyek/'.$project->slug.'/download-thumbnail');

        // Assert
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
        $this->assertStringContainsString('equipment-monitoring-system-preview.png', (string) $response->headers->get('content-disposition'));
    }

    public function test_cannot_download_thumbnail_for_draft_project(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('projects/thumbnails/draft.png', 'fake image bytes');

        $project = Project::factory()->draft()->create([
            'slug' => 'draft-project',
            'thumbnail_path' => 'projects/thumbnails/draft.png',
        ]);

        // Act
        $response = $this->get('/proyek/'.$project->slug.'/download-thumbnail');

        // Assert
        $response->assertStatus(404);
    }

    public function test_cannot_download_thumbnail_if_file_missing(): void
    {
        // Arrange
        Storage::fake('public');

        $project = Project::factory()->create([
            'slug' => 'missing-image-project',
            'thumbnail_path' => 'projects/thumbnails/nonexistent.png',
            'status' => ProjectStatus::Published,
        ]);

        // Act
        $response = $this->get('/proyek/'.$project->slug.'/download-thumbnail');

        // Assert
        $response->assertStatus(404);
    }

    public function test_user_can_download_published_project_media(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('media/projects/screenshot.png', 'fake screenshot data');

        $project = Project::factory()->create([
            'status' => ProjectStatus::Published,
        ]);

        $media = $project->media()->create([
            'path' => 'media/projects/screenshot.png',
            'caption' => 'Dashboard Tampilan',
            'sort_order' => 1,
        ]);

        // Act
        $response = $this->get('/media/'.$media->id.'/download');

        // Assert
        $response->assertStatus(200);
        $this->assertStringContainsString('dashboard-tampilan.png', (string) $response->headers->get('content-disposition'));
    }
}
