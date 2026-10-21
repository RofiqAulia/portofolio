<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Certification;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_displays_published_certifications(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $published = Certification::factory()->create([
            'title' => 'Sertifikasi Keahlian Cloud Architecture',
            'category' => 'Sertifikasi Profesi',
            'issuer' => 'AWS Certified Global',
            'is_published' => true,
        ]);

        $draft = Certification::factory()->draft()->create([
            'title' => 'Draft Penghargaan Rahasia',
            'category' => 'Penghargaan Kinerja',
        ]);

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Sertifikasi Keahlian Cloud Architecture');
        $response->assertSee('AWS Certified Global');
        $response->assertDontSee('Draft Penghargaan Rahasia');
    }

    public function test_certification_model_handles_multiple_images_and_files(): void
    {
        // Arrange
        $cert = Certification::factory()->create([
            'title' => 'Sertifikat Hak Cipta Enterprise',
            'images' => [
                'certifications/images/scan-page1.png',
                'certifications/images/scan-page2.png',
            ],
            'files' => [
                'certifications/files/sk-menteri.pdf',
                'certifications/files/lampiran-ciptaan.pdf',
                'certifications/files/arsip-hki.zip',
            ],
        ]);

        // Assert
        $this->assertTrue($cert->hasImages());
        $this->assertTrue($cert->hasFiles());
        $this->assertSame(2, $cert->getImageCount());
        $this->assertSame(3, $cert->getFileCount());
        $this->assertSame('certifications/images/scan-page1.png', $cert->getFirstImage());
        $this->assertCount(2, $cert->getImageItems());
        $this->assertCount(3, $cert->getFileItems());
    }

    public function test_user_can_download_certification_file(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('certifications/files/dokumen-hki.pdf', 'dummy pdf content');

        $cert = Certification::factory()->create([
            'title' => 'Surat Pencatatan Ciptaan SOVIA',
            'slug' => 'surat-pencatatan-ciptaan-sovia',
            'files' => ['certifications/files/dokumen-hki.pdf'],
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/sertifikasi/'.$cert->slug.'/berkas/0');

        // Assert
        $response->assertStatus(200);
        $this->assertStringContainsString('surat-pencatatan-ciptaan-sovia-file.pdf', (string) $response->headers->get('content-disposition'));
    }

    public function test_user_cannot_download_missing_certification_file(): void
    {
        // Arrange
        Storage::fake('public');

        $cert = Certification::factory()->create([
            'slug' => 'missing-file-cert',
            'files' => ['certifications/files/nonexistent.pdf'],
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/sertifikasi/'.$cert->slug.'/berkas/0');

        // Assert
        $response->assertStatus(404);
    }

    public function test_user_can_download_certification_image(): void
    {
        // Arrange
        Storage::fake('public');
        Storage::disk('public')->put('certifications/images/award-photo.png', 'dummy image content');

        $cert = Certification::factory()->create([
            'title' => 'Best Performance Trophy',
            'slug' => 'best-performance-trophy',
            'images' => ['certifications/images/award-photo.png'],
            'is_published' => true,
        ]);

        // Act
        $response = $this->get('/sertifikasi/'.$cert->slug.'/gambar/0');

        // Assert
        $response->assertStatus(200);
        $this->assertStringContainsString('best-performance-trophy-image.png', (string) $response->headers->get('content-disposition'));
    }

    public function test_admin_can_access_filament_certifications_pages(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        // Act & Assert
        $this->actingAs($admin)
            ->get('/admin/certifications')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/certifications/create')
            ->assertStatus(200);
    }
}
