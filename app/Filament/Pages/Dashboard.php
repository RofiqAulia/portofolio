<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\PageViewsChartWidget;
use App\Filament\Widgets\PortfolioTrendChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    public function getColumns(): int|array
    {
        return 2;
    }

    /**
     * @return array<class-string<Widget>>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            PageViewsChartWidget::class,
            PortfolioTrendChartWidget::class,
        ];
    }
}
