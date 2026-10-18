<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Profile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Portofolio Admin');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);
        Profile::factory()->create(['user_id' => $admin->id]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_editor_cannot_access_users_management(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::Editor,
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_site_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/site-settings');

        $response->assertStatus(200);
    }

    public function test_editor_cannot_access_site_settings_page(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::Editor,
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin/site-settings');

        $response->assertStatus(403);
    }

    public function test_admin_panel_configures_create_and_edit_redirect_to_index(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertSame('index', $panel->getResourceCreatePageRedirect());
        $this->assertSame('index', $panel->getResourceEditPageRedirect());
    }

    public function test_create_and_edit_pages_redirect_to_origin_or_index(): void
    {
        $createPage = new CreateProject;
        $createMethod = new \ReflectionMethod($createPage, 'getRedirectUrl');
        $this->assertSame(ProjectResource::getUrl('index'), $createMethod->invoke($createPage));

        $editPage = new EditProject;
        $editPage->previousUrl = '/admin/projects?tableSearch=laravel';
        $editMethod = new \ReflectionMethod($editPage, 'getRedirectUrl');
        $this->assertSame('/admin/projects?tableSearch=laravel', $editMethod->invoke($editPage));
    }
}
