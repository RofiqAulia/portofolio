<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\DocumentationType;
use App\Models\Documentation;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_documentation_index_page(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $doc = Documentation::factory()->create([
            'title' => 'Skema ERD Warehouse Master',
            'category' => 'ERD & Database',
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/dokumentasi');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Skema ERD Warehouse Master');
        $response->assertSee('ERD & Database');
    }

    public function test_user_can_filter_documentations_by_category(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $erdDoc = Documentation::factory()->create([
            'title' => 'Database ERD Diagram',
            'category' => 'ERD & Database',
            'is_published' => true,
        ]);

        $umlDoc = Documentation::factory()->create([
            'title' => 'UML Sequence Authentication',
            'category' => 'UML & Flowchart',
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/dokumentasi?kategori=ERD+%26+Database');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Database ERD Diagram');
        $response->assertDontSee('UML Sequence Authentication');
    }

    public function test_user_can_view_published_documentation_detail(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $doc = Documentation::factory()->create([
            'title' => 'Spesifikasi Arsitektur Microservices',
            'slug' => 'spesifikasi-arsitektur-microservices',
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/dokumentasi/'.$doc->slug);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Spesifikasi Arsitektur Microservices');
    }

    public function test_cannot_view_draft_documentation_detail(): void
    {
        // Arrange
        $doc = Documentation::factory()->draft()->create([
            'slug' => 'draft-documentation-secret',
        ]);

        // Act
        $response = $this->get('/dokumentasi/'.$doc->slug);

        // Assert
        $response->assertStatus(404);
    }

    public function test_user_can_download_documentation_file(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('documentations/files/blueprint.png', 'fake diagram binary data');

        $doc = Documentation::factory()->create([
            'title' => 'Blueprint Sistem Internal',
            'slug' => 'blueprint-sistem-internal',
            'type' => DocumentationType::Image,
            'file_path' => 'documentations/files/blueprint.png',
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/dokumentasi/'.$doc->slug.'/download');

        // Assert
        $response->assertStatus(200);
        $this->assertStringContainsString('blueprint-sistem-internal.png', (string) $response->headers->get('content-disposition'));
    }

    public function test_cannot_download_documentation_if_file_missing(): void
    {
        // Arrange
        Storage::fake('public');

        $doc = Documentation::factory()->create([
            'slug' => 'missing-file-doc',
            'file_path' => 'documentations/files/nonexistent.pdf',
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/dokumentasi/'.$doc->slug.'/download');

        // Assert
        $response->assertStatus(404);
    }

    public function test_landing_page_displays_blueprint_callout(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200);
        $response->assertSeeText('BLUEPRINT LAB');
        $response->assertSeeText('Buka Arsip Dokumentasi & Blueprint');
    }
}
