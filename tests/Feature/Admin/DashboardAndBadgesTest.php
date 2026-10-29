<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\DocumentationType;
use App\Enums\ExperienceType;
use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
use App\Filament\Livewire\DatabaseNotifications;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Documentations\DocumentationResource;
use App\Filament\Resources\Experiences\ExperienceResource;
use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Resources\Profiles\ProfileResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\SkillCategories\SkillCategoryResource;
use App\Filament\Resources\Technologies\TechnologyResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\LatestBlueprintsWidget;
use App\Filament\Widgets\LatestMessagesWidget;
use App\Filament\Widgets\PageViewsChartWidget;
use App\Filament\Widgets\PortfolioTrendChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\Certification;
use App\Models\Documentation;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PageView;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\Technology;
use App\Models\User;
use App\Services\MessageNotificationService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardAndBadgesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);
        Profile::factory()->create(['user_id' => $this->admin->id]);
    }

    public function test_authenticated_admin_can_render_dashboard_with_all_widgets(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertDontSee('Filter Periode Data');
        $response->assertDontSee('Dokumentasi & Blueprint Terbaru');
        $response->assertDontSee('Pesan Masuk Terbaru');
    }

    public function test_custom_dashboard_has_columns_configured(): void
    {
        $page = new Dashboard;

        $this->assertEquals(2, $page->getColumns());
        $this->assertSame([
            StatsOverviewWidget::class,
            PageViewsChartWidget::class,
            PortfolioTrendChartWidget::class,
        ], $page->getWidgets());
    }

    public function test_sidebar_navigation_badges_return_correct_counts(): void
    {
        Project::factory()->count(3)->create();
        Experience::factory()->count(5)->create(['type' => ExperienceType::Internship]);
        Documentation::factory()->count(4)->create([
            'type' => DocumentationType::Image,
            'category' => 'Arsitektur Sistem',
        ]);
        Certification::factory()->count(2)->create();
        SkillCategory::factory()->count(3)->create();
        Technology::factory()->count(6)->create();
        Message::factory()->count(4)->create(['status' => MessageStatus::New]);

        $this->assertSame('3', ProjectResource::getNavigationBadge());
        $this->assertSame('5', ExperienceResource::getNavigationBadge());
        $this->assertSame('4', DocumentationResource::getNavigationBadge());
        $this->assertSame('2', CertificationResource::getNavigationBadge());
        $this->assertSame('3', SkillCategoryResource::getNavigationBadge());
        $this->assertSame('6', TechnologyResource::getNavigationBadge());
        $this->assertSame('4', MessageResource::getNavigationBadge());
        $this->assertSame('danger', MessageResource::getNavigationBadgeColor());
        $this->assertSame('1', UserResource::getNavigationBadge());
        $this->assertSame('1', ProfileResource::getNavigationBadge());
    }

    public function test_message_navigation_badge_only_counts_unread_messages(): void
    {
        Message::factory()->count(2)->create(['status' => MessageStatus::New]);
        Message::factory()->count(3)->create(['status' => MessageStatus::Read]);
        Message::factory()->count(1)->create(['status' => MessageStatus::Replied]);

        $this->assertSame('2', MessageResource::getNavigationBadge());

        Message::where('status', MessageStatus::New)->update(['status' => MessageStatus::Read]);

        $this->assertNull(MessageResource::getNavigationBadge());
    }

    public function test_message_read_and_unread_status_syncs_with_notifications(): void
    {
        $message = Message::factory()->create(['status' => MessageStatus::New]);
        $notificationService = app(MessageNotificationService::class);
        $notificationService->notifyAdminsInApp($message);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'read_at' => null,
        ]);

        $message->update(['status' => MessageStatus::Read]);

        $this->assertNotNull($message->fresh()->read_at);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $this->admin->id,
            'read_at' => null,
        ]);

        $message->update(['status' => MessageStatus::New]);

        $this->assertNull($message->fresh()->read_at);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'read_at' => null,
        ]);
    }

    public function test_database_notifications_component_shows_unread_messages_count_and_content(): void
    {
        Message::query()->delete();
        $this->admin->notifications()->delete();

        Message::factory()->create([
            'name' => 'Budi Santoso',
            'subject' => 'Proyek Fintech Cloud',
            'status' => MessageStatus::New,
        ]);

        $this->actingAs($this->admin);

        $component = Livewire::test(DatabaseNotifications::class);
        $this->assertEquals(1, $component->instance()->getUnreadNotificationsCount());
        $component->assertSee('Budi Santoso');
        $component->assertSee('Proyek Fintech Cloud');
        $component->assertSee('Buka Pesan');
        $component->assertSee('Tandai Dibaca');

        $component->call('markAllNotificationsAsRead');
        $this->assertEquals(0, $component->instance()->getUnreadNotificationsCount());
        $this->assertEquals(0, Message::where('status', MessageStatus::New)->count());
    }

    public function test_individual_notification_can_be_marked_as_read_and_unread_via_events(): void
    {
        Message::query()->delete();
        $this->admin->notifications()->delete();

        $message = Message::factory()->create([
            'name' => 'Ahmad Dahlan',
            'subject' => 'Konsultasi Arsitektur',
            'status' => MessageStatus::New,
        ]);

        $this->actingAs($this->admin);

        $component = Livewire::test(DatabaseNotifications::class);
        $notification = $this->admin->notifications()->firstOrFail();

        // Panggil markNotificationAsRead
        $component->call('markNotificationAsRead', $notification->id);
        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertSame(MessageStatus::Read, $message->fresh()->status);

        // Panggil markNotificationAsUnread
        $component->call('markNotificationAsUnread', $notification->id);
        $this->assertNull($notification->fresh()->read_at);
        $this->assertSame(MessageStatus::New, $message->fresh()->status);
    }

    public function test_stats_overview_widget_returns_daily_weekly_monthly_and_blueprint_stats(): void
    {
        PageView::factory()->create(['created_at' => now()]);
        PageView::factory()->create(['created_at' => now()->subDays(2)]);
        PageView::factory()->create(['created_at' => now()->subDays(10)]);
        Documentation::factory()->create([
            'category' => 'Arsitektur Sistem & Blueprint',
            'is_published' => true,
        ]);

        Livewire::test(StatsOverviewWidget::class)
            ->assertSee('Pengunjung Hari Ini')
            ->assertSee('Pengunjung Minggu Ini')
            ->assertSee('Pengunjung Bulan Ini')
            ->assertSee('Blueprint & Dokumen');
    }

    public function test_page_views_chart_widget_returns_chart_data_for_different_filters(): void
    {
        PageView::factory()->create(['created_at' => now()]);
        PageView::factory()->create(['created_at' => now()->subDays(3)]);

        $component = Livewire::test(PageViewsChartWidget::class)
            ->assertSet('filter', 'month');

        $component->set('filter', 'today');
        $component->assertSet('filter', 'today');

        $component->set('filter', 'week');
        $component->assertSet('filter', 'week');

        $component->set('filter', 'year');
        $component->assertSet('filter', 'year');
    }

    public function test_portfolio_trend_chart_widget_is_bar_chart_and_supports_filters(): void
    {
        Project::factory()->create(['status' => ProjectStatus::Published]);
        Experience::factory()->create(['type' => ExperienceType::Internship]);

        $component = Livewire::test(PortfolioTrendChartWidget::class)
            ->assertSet('filter', 'this_year')
            ->assertSee('Tren Proyek & Pengalaman');

        $widget = new PortfolioTrendChartWidget;
        $refMethod = new \ReflectionMethod($widget, 'getType');
        $this->assertSame('bar', $refMethod->invoke($widget));

        $component->set('filter', '6_months');
        $component->assertSet('filter', '6_months');

        $component->set('filter', 'all_time');
        $component->assertSet('filter', 'all_time');
    }

    public function test_latest_blueprints_widget_displays_blueprint_records(): void
    {
        $project = Project::factory()->create(['title' => 'E-Commerce Cloud']);
        Documentation::factory()->create([
            'title' => 'Arsitektur Microservices',
            'project_id' => $project->id,
            'category' => 'Arsitektur Sistem',
            'type' => DocumentationType::Image,
            'is_published' => true,
        ]);

        Livewire::test(LatestBlueprintsWidget::class)
            ->assertSee('Arsitektur Microservices')
            ->assertSee('E-Commerce Cloud');
    }

    public function test_admin_panel_registers_active_widgets_and_excludes_blueprint_and_messages_from_dashboard(): void
    {
        $panel = Filament::getPanel('admin');
        $widgets = $panel->getWidgets();

        $this->assertContains(StatsOverviewWidget::class, $widgets);
        $this->assertContains(PageViewsChartWidget::class, $widgets);
        $this->assertContains(PortfolioTrendChartWidget::class, $widgets);
        $this->assertNotContains(LatestBlueprintsWidget::class, $widgets);
        $this->assertNotContains(LatestMessagesWidget::class, $widgets);
    }

    public function test_sync_message_notifications_command_syncs_unread_messages(): void
    {
        Message::query()->delete();
        $this->admin->notifications()->delete();

        Message::factory()->create([
            'name' => 'Siti Aminah',
            'subject' => 'Tawaran Kerjasama',
            'status' => MessageStatus::New,
        ]);

        $this->artisan('messages:sync-notifications')
            ->expectsOutput('Menyinkronkan notifikasi pesan belum dibaca...')
            ->expectsOutput('Berhasil memproses 1 pesan belum dibaca.')
            ->assertSuccessful();

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'read_at' => null,
        ]);
    }
}
