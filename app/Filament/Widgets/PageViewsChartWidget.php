<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\PageView;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PageViewsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Kunjungan (30 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $startDate = now()->subDays(29)->startOfDay();

        $views = PageView::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $days = [];
        $counts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = Carbon::parse($date)->translatedFormat('d M');
            $days[] = $label;
            $counts[] = $views[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Page Views',
                    'data' => $counts,
                    'borderColor' => '#2F5BFF',
                    'backgroundColor' => 'rgba(47, 91, 255, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
