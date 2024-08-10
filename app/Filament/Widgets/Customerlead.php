<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Customer;

class CustomerLead extends BaseWidget
{
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Customer::withCount('orders')
                    ->having('orders_count', '>', 0) 
                    ->orderBy('orders_count', 'desc')
                    ->limit(5) 
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Jumlah Order'),
            ]);
    }
}
