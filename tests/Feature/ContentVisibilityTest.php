<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Enums\SkillType;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_only_displays_published_projects(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $publishedProject = Project::factory()->create([
            'title' => 'Aplikasi Terbit Publik',
            'status' => ProjectStatus::Published,
        ]);

        $draftProject = Project::factory()->draft()->create([
            'title' => 'Aplikasi Rahasia Draft',
        ]);

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Aplikasi Terbit Publik');
        $response->assertDontSee('Aplikasi Rahasia Draft');
    }

    public function test_landing_page_only_displays_published_experiences(): void
    {
        // Arrange
        $admin = User::factory()->create();
        Profile::factory()->create(['user_id' => $admin->id]);

        $publishedExp = Experience::factory()->internship()->create([
            'position' => 'Senior Backend Intern',
            'status' => ProjectStatus::Published,
        ]);

        $draftExp = Experience::factory()->draft()->create([
            'position' => 'Draft Intern Unreleased',
        ]);

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Senior Backend Intern');
        $response->assertDontSee('Draft Intern Unreleased');
    }

    public function test_landing_page_displays_profile_photo_when_available(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'full_name' => 'M. Rofiq Aulia',
            'photo_path' => 'profiles/photos/test_avatar.png',
        ]);

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('storage/profiles/photos/test_avatar.png');
    }

    public function test_landing_page_displays_fallback_when_photo_is_empty(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'full_name' => 'Rofiq No Photo',
            'photo_path' => null,
        ]);

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('bi-person-bounding-box');
    }

    public function test_landing_page_renders_project_slider_when_projects_exist(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        Project::factory()->create([
            'title' => 'Project Slidable Satu',
            'status' => ProjectStatus::Published,
        ]);
        Project::factory()->create([
            'title' => 'Project Slidable Dua',
            'status' => ProjectStatus::Published,
        ]);
        Project::factory()->create([
            'title' => 'Project Slidable Tiga',
            'status' => ProjectStatus::Published,
        ]);

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('projectSliderTrack');
        $response->assertSee('project-slide-item');
        $response->assertSee('Project Slidable Satu');
        $response->assertSee('Project Slidable Dua');
        $response->assertSee('Project Slidable Tiga');
    }

    public function test_landing_page_renders_hard_skills_slider_when_more_than_seven_skills_exist(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $category = SkillCategory::factory()->create([
            'name' => 'Hard Skills',
            'type' => SkillType::Hard,
        ]);

        for ($i = 1; $i <= 8; $i++) {
            Skill::factory()->create([
                'skill_category_id' => $category->id,
                'name' => "Skill Ke-{$i}",
                'is_visible' => true,
                'sort_order' => $i,
            ]);
        }

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('hardSkillsCarousel');
        $response->assertSee('carousel-item');
        $response->assertSee('Skill Ke-1');
        $response->assertSee('Skill Ke-8');
    }

    public function test_landing_page_renders_hard_skills_statically_when_seven_or_fewer_skills_exist(): void
    {
        // Arrange
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $category = SkillCategory::factory()->create([
            'name' => 'Hard Skills',
            'type' => SkillType::Hard,
        ]);

        for ($i = 1; $i <= 7; $i++) {
            Skill::factory()->create([
                'skill_category_id' => $category->id,
                'name' => "Static Skill-{$i}",
                'is_visible' => true,
                'sort_order' => $i,
            ]);
        }

        // Act
        $response = $this->get(route('portfolio.home'));

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee('hardSkillsCarousel');
        $response->assertSee('Static Skill-1');
        $response->assertSee('Static Skill-7');
    }
}
