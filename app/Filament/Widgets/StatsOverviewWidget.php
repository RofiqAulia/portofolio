<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\MessageStatus;
use App\Enums\ProjectStatus;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PageView;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $monthlyViews = PageView::where('created_at', '>=', now()->startOfMonth())->count();
        $publishedProjects = Project::where('status', ProjectStatus::Published)->count();
        $newMessages = Message::where('status', MessageStatus::New)->count();
        $totalDrafts = Project::where('status', ProjectStatus::Draft)->count()
            + Experience::where('status', ProjectStatus::Draft)->count();

        return [
            Stat::make('Pengunjung Bulan Ini', (string) $monthlyViews)
                ->description('Total page views bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Proyek Terbit', (string) $publishedProjects)
                ->description('Proyek yang tampil ke publik')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
            Stat::make('Pesan Baru', (string) $newMessages)
                ->description('Pesan belum dibaca')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($newMessages > 0 ? 'danger' : 'gray'),
            Stat::make('Draft Konten', (string) $totalDrafts)
                ->description('Proyek & Pengalaman status draft')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),
        ];
    }
}
