<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Documentation;
use App\Models\PageView;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        // Ambil data view 6 bulan terakhir dalam 1 query tunggal yang di-cache di memori (menggantikan 16 query berulang)
        $historyStart = now()->subMonths(5)->startOfMonth();
        $recentViews = PageView::where('created_at', '>=', $historyStart)->get(['created_at']);

        // Daily stats
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $yesterdayStart = now()->subDay()->startOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();

        $todayViews = $recentViews->filter(fn ($v) => $v->created_at >= $todayStart && $v->created_at <= $todayEnd)->count();
        $yesterdayViews = $recentViews->filter(fn ($v) => $v->created_at >= $yesterdayStart && $v->created_at <= $yesterdayEnd)->count();
        $diffDay = $todayViews - $yesterdayViews;
        $diffDaySign = $diffDay > 0 ? '+' : '';

        // 7-day sparkline for daily
        $recentDaily = $recentViews->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'))
            ->map->count();

        $daySparkline = [];
        for ($i = 6; $i >= 0; $i--) {
            $key = now()->subDays($i)->format('Y-m-d');
            $daySparkline[] = $recentDaily->get($key, 0);
        }

        // Weekly stats
        $thisWeekStart = now()->startOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $thisWeekViews = $recentViews->filter(fn ($v) => $v->created_at >= $thisWeekStart)->count();
        $lastWeekViews = $recentViews->filter(fn ($v) => $v->created_at >= $lastWeekStart && $v->created_at <= $lastWeekEnd)->count();
        $diffWeek = $thisWeekViews - $lastWeekViews;
        $diffWeekSign = $diffWeek > 0 ? '+' : '';

        $weeklySparkline = [];
        for ($w = 3; $w >= 0; $w--) {
            $start = now()->subWeeks($w)->startOfWeek();
            $end = now()->subWeeks($w)->endOfWeek();
            $weeklySparkline[] = $recentViews->filter(fn ($v) => $v->created_at >= $start && $v->created_at <= $end)->count();
        }

        // Monthly stats
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $thisMonthViews = $recentViews->filter(fn ($v) => $v->created_at >= $thisMonthStart)->count();
        $lastMonthViews = $recentViews->filter(fn ($v) => $v->created_at >= $lastMonthStart && $v->created_at <= $lastMonthEnd)->count();
        $diffMonth = $thisMonthViews - $lastMonthViews;
        $diffMonthSign = $diffMonth > 0 ? '+' : '';

        $monthlySparkline = [];
        for ($m = 5; $m >= 0; $m--) {
            $start = now()->subMonths($m)->startOfMonth();
            $end = now()->subMonths($m)->endOfMonth();
            $monthlySparkline[] = $recentViews->filter(fn ($v) => $v->created_at >= $start && $v->created_at <= $end)->count();
        }

        // Blueprint & Documentation stats
        $totalBlueprints = Documentation::count();
        $publishedBlueprints = Documentation::where('is_published', true)->count();
        $archBlueprints = Documentation::where('category', 'like', '%Arsitektur%')->count();
        $blueprintDesc = "{$publishedBlueprints} terbit".($archBlueprints > 0 ? " • {$archBlueprints} arsitektur" : '');

        $blueprintSparkline = [];
        for ($m = 5; $m >= 0; $m--) {
            $start = now()->subMonths($m)->startOfMonth();
            $end = now()->subMonths($m)->endOfMonth();
            $blueprintSparkline[] = Documentation::whereBetween('created_at', [$start, $end])->count();
        }

        $blueprintStat = Stat::make('Blueprint & Dokumen', (string) $totalBlueprints)
            ->description($blueprintDesc)
            ->descriptionIcon('heroicon-m-document-text')
            ->color('primary')
            ->chart($blueprintSparkline);

        $todayStat = Stat::make('Pengunjung Hari Ini', (string) $todayViews)
            ->description("{$diffDaySign}{$diffDay} vs kemarin")
            ->descriptionIcon($diffDay >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($diffDay >= 0 ? 'success' : 'danger')
            ->chart($daySparkline);

        $monthStat = Stat::make('Pengunjung Bulan Ini', (string) $thisMonthViews)
            ->description("{$diffMonthSign}{$diffMonth} vs bulan lalu")
            ->descriptionIcon($diffMonth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($diffMonth >= 0 ? 'success' : 'danger')
            ->chart($monthlySparkline);

        if ($startDate || $endDate) {
            $filteredViews = PageView::query()
                ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate))
                ->count();

            $dateLabel = trim(($startDate ? "dari {$startDate} " : '').($endDate ? "s/d {$endDate}" : ''));

            $filteredStat = Stat::make('Kunjungan Terfilter', (string) $filteredViews)
                ->description($dateLabel !== '' ? $dateLabel : 'Filter aktif')
                ->descriptionIcon('heroicon-m-funnel')
                ->color('info');

            return [
                $filteredStat,
                $todayStat,
                $monthStat,
                $blueprintStat,
            ];
        }

        $weekStat = Stat::make('Pengunjung Minggu Ini', (string) $thisWeekViews)
            ->description("{$diffWeekSign}{$diffWeek} vs minggu lalu")
            ->descriptionIcon($diffWeek >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($diffWeek >= 0 ? 'success' : 'danger')
            ->chart($weeklySparkline);

        return [
            $todayStat,
            $weekStat,
            $monthStat,
            $blueprintStat,
        ];
    }
}
