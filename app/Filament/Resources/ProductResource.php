<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'product management';

    protected static ?string $navigationLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->minLength(3)
                        ->label('Nama'),
                        
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->minLength(3)
                        ->label('Slug')
                        ->dehydrated(false)
                        ->reactive(),
                ])->columns(2), 

                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->label('Harga'),
                    
                    Forms\Components\TextInput::make('stock')
                        ->required()
                        ->label('Stok'),
                ])->columns(2),
                Forms\Components\Section::make([
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),
                    
                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->required()
                        ->columnSpan(2),
                    
                    Forms\Components\Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('supplier', 'name')
                        ->required()
                        ->columnSpan(2),
                ])->columns(2),

                Forms\Components\Section::make([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar')
                        ->image()
                        ->columnSpanFull(), 
                ]),
            ])
            ->columns([
                'sm' => 1,
                'md' => 2,
            ]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Produk'),
                
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga'),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok'),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi'),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi yang diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
