<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Pembelian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supplier_id')
                    ->required()
                    ->relationship('supplier', 'name')
                    ->label('Supplier'),

                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->label('User'),

                Forms\Components\Select::make('product_id')
                    ->required()
                    ->relationship('product', 'name')
                    ->label('Produk'),

                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(1000000)
                    ->step(1),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Harga')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(1000000)
                    ->step(1),

                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->disabled()
                    ->default(function ($record) {
                        return $record ? $record->quantity * $record->price : 0;
                    }),

                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Sudah Bayar',
                        'installments' => 'Cicilan',
                    ]),

                Forms\Components\Select::make('payment_method')
                    ->required()
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer',
                        'virtual_account' => 'Virtual Account',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')->label('Supplier'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('product.name')->label('Produk'),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah'),
                Tables\Columns\TextColumn::make('price')->label('Harga'),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR', true),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('payment_method')->label('Metode Pembayaran'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
