<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class OrdersStats extends ChartWidget
{
    public ?string $filter = 'week';

    protected static ?string $width = '100%';

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

        $ordersData = Trend::model(Order::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->$interval()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Pesanan dari pelanggan',
                    'data' => $ordersData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $ordersData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
