<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_profile_returns_correct_json_structure(): void
    {
        // Arrange
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        SocialLink::factory()->create(['profile_id' => $profile->id, 'is_active' => true]);

        // Act
        $response = $this->getJson('/api/v1/profile');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'full_name',
                'headline',
                'tagline',
                'bio',
                'photo_url',
                'cv_url',
                'email',
                'phone',
                'location',
                'quote_text',
                'quote_author',
                'is_open_to_work',
                'social_links' => [
                    '*' => ['id', 'platform', 'icon', 'url', 'sort_order'],
                ],
            ],
        ]);
    }

    public function test_get_social_links_returns_only_active_links(): void
    {
        // Arrange
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        SocialLink::factory()->create(['profile_id' => $profile->id, 'platform' => 'GitHub Active', 'is_active' => true]);
        SocialLink::factory()->create(['profile_id' => $profile->id, 'platform' => 'Twitter Inactive', 'is_active' => false]);

        // Act
        $response = $this->getJson('/api/v1/social-links');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(['platform' => 'GitHub Active']);
        $response->assertJsonMissing(['platform' => 'Twitter Inactive']);
    }

    public function test_get_skills_returns_categories_with_visible_skills(): void
    {
        // Arrange
        $cat = SkillCategory::factory()->create(['name' => 'Backend Skills']);
        $cat->skills()->create([
            'name' => 'Laravel 12',
            'level' => 95,
            'is_visible' => true,
            'sort_order' => 1,
        ]);
        $cat->skills()->create([
            'name' => 'Hidden Skill',
            'level' => 50,
            'is_visible' => false,
            'sort_order' => 2,
        ]);

        // Act
        $response = $this->getJson('/api/v1/skills');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Laravel 12']);
        $response->assertJsonMissing(['name' => 'Hidden Skill']);
    }

    public function test_get_projects_returns_paginated_published_projects_only(): void
    {
        // Arrange
        Project::factory()->create([
            'title' => 'Proyek Publik',
            'status' => ProjectStatus::Published,
        ]);
        Project::factory()->draft()->create([
            'title' => 'Proyek Draft Rahasia',
        ]);

        // Act
        $response = $this->getJson('/api/v1/projects');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'summary', 'role', 'is_featured', 'sort_order'],
            ],
            'links',
            'meta',
        ]);
        $response->assertJsonFragment(['title' => 'Proyek Publik']);
        $response->assertJsonMissing(['title' => 'Proyek Draft Rahasia']);
    }

    public function test_get_project_by_slug_returns_404_when_draft_or_missing(): void
    {
        // Arrange
        $draft = Project::factory()->draft()->create(['slug' => 'draft-api-test']);

        // Act & Assert
        $this->getJson('/api/v1/projects/draft-api-test')->assertStatus(404);
        $this->getJson('/api/v1/projects/non-existent-slug')->assertStatus(404);
    }

    public function test_get_experiences_can_filter_by_type(): void
    {
        // Arrange
        Experience::factory()->internship()->create([
            'position' => 'Magang Programmer',
            'status' => ProjectStatus::Published,
        ]);
        Experience::factory()->organization()->create([
            'position' => 'Ketua Organisasi',
            'status' => ProjectStatus::Published,
        ]);

        // Act & Assert (internship filter)
        $internResponse = $this->getJson('/api/v1/experiences?type=internship');
        $internResponse->assertStatus(200);
        $internResponse->assertJsonFragment(['position' => 'Magang Programmer']);
        $internResponse->assertJsonMissing(['position' => 'Ketua Organisasi']);

        // Act & Assert (organization filter)
        $orgResponse = $this->getJson('/api/v1/experiences?type=organization');
        $orgResponse->assertStatus(200);
        $orgResponse->assertJsonFragment(['position' => 'Ketua Organisasi']);
        $orgResponse->assertJsonMissing(['position' => 'Magang Programmer']);
    }

    public function test_post_message_creates_message_and_returns_201(): void
    {
        // Act
        $response = $this->postJson('/api/v1/messages', [
            'name' => 'API Client',
            'email' => 'client@api.com',
            'subject' => 'Inquiry via API',
            'body' => 'Pesan pengujian melalui endpoint API v1 terstruktur.',
            'company_website' => '',
        ]);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'subject', 'status', 'created_at'],
        ]);

        $this->assertDatabaseHas('messages', [
            'email' => 'client@api.com',
            'status' => MessageStatus::New->value,
        ]);
    }

    public function test_post_message_returns_422_when_validation_fails(): void
    {
        // Act
        $response = $this->postJson('/api/v1/messages', [
            'name' => '',
            'email' => 'not-an-email',
            'body' => '',
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }
}
