<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;

class TopSellingProducts extends BaseWidget
{
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Product::select('products.*')
                    ->selectSub(function ($query) {
                        $query->from('order_items')
                            ->selectRaw('SUM(quantity)')
                            ->whereColumn('order_items.product_id', 'products.id')
                            ->groupBy('product_id');
                    }, 'total_sold')
                    ->orderBy('total_sold', 'desc')
                    ->having('total_sold', '>', 0)
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->formatStateUsing(function ($state) {
                        if ($state <= 0) {
                            return "Stok Habis";
                        } elseif ($state <= 10) {
                            return "Stok Hampir Habis ({$state})";
                        } else {
                            return $state;
                        }
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Jumlah Terjual'),
            ])
            ->defaultSort('total_sold', 'desc');
    }
}
