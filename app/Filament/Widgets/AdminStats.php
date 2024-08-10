<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Customer;

class AdminStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Penjualan', 'Rp' . number_format(Order::sum('total'), 0, ',', '.')),
            Stat::make('Total Pembelian', 'Rp' . number_format(Purchase::getTotalOverall(), 0, ',', '.')),
            Stat::make('Total Pelanggan', Customer::count()),
        ];
    }
}
