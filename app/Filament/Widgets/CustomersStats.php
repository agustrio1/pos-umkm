<?php 

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Customer;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class CustomersStats extends ChartWidget
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

        $customersData = Trend::model(Customer::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->$interval()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Pelanggan',
                    'data' => $customersData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
            ],
            'labels' => $customersData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
