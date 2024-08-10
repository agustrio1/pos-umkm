<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('customer_id')
                                    ->required()
                                    ->relationship('customer', 'name')
                                    ->label('Pelanggan')
                                    ->columnSpan(2),

                                Forms\Components\Repeater::make('order_items')
                                    ->label('Produk')
                                    ->relationship('orderItems')
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->required()
                                            ->options(Product::all()->pluck('name', 'id'))
                                            ->label('Produk')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('price', $product->price);
                                                    OrderResource::updateTotal($get, $set);
                                                }
                                            }),

                                        Forms\Components\TextInput::make('quantity')
                                            ->required()
                                            ->label('Kuantitas')
                                            ->numeric()
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                OrderResource::updateTotal($get, $set);
                                            }),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->minItems(1)
                                    ->maxItems(10)
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('discount')
                                    ->label('Diskon')
                                    ->numeric()
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        OrderResource::updateTotal($get, $set);
                                    }),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0),
                            ]),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->label('Status')
                                    ->options([
                                        'unpaid' => 'Unpaid',
                                        'paid' => 'Paid',
                                        'installments' => 'Installments',
                                    ]),

                                Forms\Components\Select::make('payment_method')
                                    ->required()
                                    ->label('Metode pembayaran')
                                    ->options([
                                        'cash' => 'Cash',
                                        'transfer' => 'Transfer',
                                        'virtual-account' => 'Virtual Account',
                                        'qris' => 'QRIS',
                                    ]),
                            ]),
                    ]),
            ]);
    }


    // Menambahkan metode statis untuk memperbarui total
    public static function updateTotal($get, $set)
    {
        $orderItems = $get('order_items') ?? [];
        $total = 0;

        // Pastikan $orderItems adalah array dan bukan null
        if (is_array($orderItems)) {
            foreach ($orderItems as $item) {
                // Cek apakah item adalah array dan memiliki key yang diharapkan
                if (is_array($item)) {
                    $total += ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                }
            }
        }

        $discount = $get('discount') ?? 0;
        $total -= $discount;
        $set('total', $total);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Kustomer'),
                Tables\Columns\TextColumn::make('orderItems')
                    ->label('Produk')
                    ->formatStateUsing(function ($record) {
                        return collect($record->orderItems)->map(function ($item) {
                            return $item->product->name . ' (' . $item->quantity . ' x Rp' . number_format($item->price, 0, ',', '.') . ')';
                        })->implode('<br>');
                    })->html(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('Diskon')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('payment_method')->label('Metode Pembayaran'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
