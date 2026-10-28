<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class PageViewsChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '280px';

    public ?string $filter = 'month';

    public function getHeading(): string|Htmlable|null
    {
        return match ($this->filter) {
            'today' => 'Grafik Kunjungan (Hari Ini)',
            'week' => 'Grafik Kunjungan (Minggu Ini)',
            'year' => 'Grafik Kunjungan (Tahun Ini)',
            default => 'Grafik Kunjungan (30 Hari)',
        };
    }

    public function getDescription(): string|Htmlable|null
    {
        return 'Aktivitas page views dan tren trafik pengunjung portofolio';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini (Per Jam)',
            'week' => 'Minggu Ini (7 Hari)',
            'month' => 'Bulan Ini (30 Hari)',
            'year' => 'Tahun Ini (12 Bulan)',
        ];
    }

    protected function getData(): array
    {
        return match ($this->filter) {
            'today' => $this->getTodayData(),
            'week' => $this->getWeeklyData(),
            'year' => $this->getYearlyData(),
            default => $this->getMonthlyData(),
        };
    }

    protected function getTodayData(): array
    {
        $views = PageView::where('created_at', '>=', now()->startOfDay())
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('H:00'))
            ->map->count();

        $labels = [];
        $counts = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $label = sprintf('%02d:00', $hour);
            $labels[] = $label;
            $counts[] = $views->get($label, 0);
        }

        return $this->formatChartData('Kunjungan Per Jam', $labels, $counts);
    }

    protected function getWeeklyData(): array
    {
        $startDate = now()->subDays(6)->startOfDay();

        $views = PageView::where('created_at', '>=', $startDate)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'))
            ->map->count();

        $labels = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('D, d M');
            $counts[] = $views->get($dateKey, 0);
        }

        return $this->formatChartData('Kunjungan Harian', $labels, $counts);
    }

    protected function getMonthlyData(): array
    {
        $startDate = now()->subDays(29)->startOfDay();

        $views = PageView::where('created_at', '>=', $startDate)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'))
            ->map->count();

        $labels = [];
        $counts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');
            $counts[] = $views->get($dateKey, 0);
        }

        return $this->formatChartData('Total Page Views', $labels, $counts);
    }

    protected function getYearlyData(): array
    {
        $startDate = now()->subMonths(11)->startOfMonth();

        $views = PageView::where('created_at', '>=', $startDate)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->map->count();

        $labels = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M Y');
            $counts[] = $views->get($key, 0);
        }

        return $this->formatChartData('Kunjungan Bulanan', $labels, $counts);
    }

    protected function formatChartData(string $label, array $labels, array $counts): array
    {
        return [
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $counts,
                    'borderColor' => '#2F5BFF',
                    'backgroundColor' => 'rgba(47, 91, 255, 0.12)',
                    'borderWidth' => 2.5,
                    'fill' => true,
                    'tension' => 0.35,
                    'pointBackgroundColor' => '#2F5BFF',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => count($labels) > 20 ? 2 : 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 16,
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
