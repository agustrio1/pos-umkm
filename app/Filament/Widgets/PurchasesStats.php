<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Purchase;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class PurchasesStats extends ChartWidget
{
    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            'week' => 'Minggu ini',
            'month' => 'Satu Bulan Terakhir',
            'year' => 'Tahun ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        switch ($activeFilter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $interval = 'perHour';
                break;

            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $interval = 'perDay';
                break;

            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $interval = 'perDay';
                break;

            case 'year':
            default:
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $interval = 'perMonth';
                break;
        }

        $purchasesData = Trend::model(Purchase::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->$interval()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Pembelian dari supplier',
                    'data' => $purchasesData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $purchasesData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
