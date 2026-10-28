<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\DocumentationType;
use App\Enums\ExperienceType;
use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Enums\UserRole;
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
use Filament\Facades\Filament;
use Filament\Schemas\Schema;
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
        $response->assertSee('Filter Periode Data');
    }

    public function test_custom_dashboard_schema_has_filter_form_components(): void
    {
        $page = new Dashboard;
        $schema = Schema::make($page);
        $configuredSchema = $page->filtersForm($schema);

        $this->assertNotEmpty($configuredSchema->getComponents());
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

    public function test_admin_panel_registers_all_five_widgets(): void
    {
        $panel = Filament::getPanel('admin');
        $widgets = $panel->getWidgets();

        $this->assertContains(StatsOverviewWidget::class, $widgets);
        $this->assertContains(PageViewsChartWidget::class, $widgets);
        $this->assertContains(PortfolioTrendChartWidget::class, $widgets);
        $this->assertContains(LatestBlueprintsWidget::class, $widgets);
        $this->assertContains(LatestMessagesWidget::class, $widgets);
    }
}
