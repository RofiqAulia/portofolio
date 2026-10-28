<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Experience;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;

class PortfolioTrendChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '280px';

    public ?string $filter = 'this_year';

    public function getHeading(): string|Htmlable|null
    {
        return 'Tren Proyek & Pengalaman';
    }

    public function getDescription(): string|Htmlable|null
    {
        return 'Perbandingan akumulasi proyek dan riwayat pengalaman kerja';
    }

    protected function getFilters(): ?array
    {
        return [
            'this_year' => 'Tahun Ini ('.now()->year.')',
            '6_months' => '6 Bulan Terakhir',
            'all_time' => 'Tahunan (Per Tahun)',
        ];
    }

    protected function getData(): array
    {
        $startDateFilter = $this->pageFilters['startDate'] ?? null;
        $endDateFilter = $this->pageFilters['endDate'] ?? null;

        if ($startDateFilter || $endDateFilter) {
            return $this->getCustomRangeData($startDateFilter, $endDateFilter);
        }

        return match ($this->filter) {
            '6_months' => $this->getSixMonthsData(),
            'all_time' => $this->getAllTimeData(),
            default => $this->getThisYearData(),
        };
    }

    protected function getThisYearData(): array
    {
        $year = now()->year;

        $projects = Project::whereYear('created_at', $year)
            ->get(['created_at'])
            ->groupBy(fn ($item) => (int) $item->created_at->format('n'))
            ->map->count();

        $experiences = Experience::whereYear('created_at', $year)
            ->get(['created_at'])
            ->groupBy(fn ($item) => (int) $item->created_at->format('n'))
            ->map->count();

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $labels = [];
        $projData = [];
        $expData = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = $monthNames[$m - 1];
            $projData[] = $projects->get($m, 0);
            $expData[] = $experiences->get($m, 0);
        }

        return $this->buildChartDataset($labels, $projData, $expData);
    }

    protected function getSixMonthsData(): array
    {
        $start = now()->subMonths(5)->startOfMonth();

        $projects = Project::where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->map->count();

        $experiences = Experience::where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->map->count();

        $labels = [];
        $projData = [];
        $expData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M Y');
            $projData[] = $projects->get($key, 0);
            $expData[] = $experiences->get($key, 0);
        }

        return $this->buildChartDataset($labels, $projData, $expData);
    }

    protected function getAllTimeData(): array
    {
        $startYear = now()->year - 4;

        $projects = Project::whereYear('created_at', '>=', $startYear)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y'))
            ->map->count();

        $experiences = Experience::whereYear('created_at', '>=', $startYear)
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y'))
            ->map->count();

        $labels = [];
        $projData = [];
        $expData = [];

        for ($y = $startYear; $y <= now()->year; $y++) {
            $key = (string) $y;
            $labels[] = $key;
            $projData[] = $projects->get($key, 0);
            $expData[] = $experiences->get($key, 0);
        }

        return $this->buildChartDataset($labels, $projData, $expData);
    }

    protected function getCustomRangeData(?string $start, ?string $end): array
    {
        $startDate = $start ? Carbon::parse($start)->startOfDay() : now()->subMonths(5)->startOfMonth();
        $endDate = $end ? Carbon::parse($end)->endOfDay() : now()->endOfDay();

        $projects = Project::whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->map->count();

        $experiences = Experience::whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at'])
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->map->count();

        $diffInMonths = max(1, (int) $startDate->diffInMonths($endDate) + 1);
        $labels = [];
        $projData = [];
        $expData = [];

        for ($i = 0; $i < $diffInMonths; $i++) {
            $date = (clone $startDate)->addMonths($i);
            if ($date->greaterThan($endDate)) {
                break;
            }
            $key = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M Y');
            $projData[] = $projects->get($key, 0);
            $expData[] = $experiences->get($key, 0);
        }

        return $this->buildChartDataset($labels, $projData, $expData);
    }

    /**
     * @param  array<string>  $labels
     * @param  array<int>  $projData
     * @param  array<int>  $expData
     * @return array<string, mixed>
     */
    protected function buildChartDataset(array $labels, array $projData, array $expData): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Proyek Baru',
                    'data' => $projData,
                    'backgroundColor' => 'rgba(47, 91, 255, 0.85)',
                    'hoverBackgroundColor' => '#2F5BFF',
                    'borderColor' => '#2F5BFF',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                    'barPercentage' => 0.75,
                    'categoryPercentage' => 0.85,
                ],
                [
                    'label' => 'Pengalaman Baru',
                    'data' => $expData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.85)',
                    'hoverBackgroundColor' => '#10B981',
                    'borderColor' => '#10B981',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                    'barPercentage' => 0.75,
                    'categoryPercentage' => 0.85,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
