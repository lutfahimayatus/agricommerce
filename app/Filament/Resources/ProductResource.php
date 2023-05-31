<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'data produk';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Produk';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    FileUpload::make('picture')
                        ->label('Gambar Produk')
                        ->image(),
                ])
                    ->columnSpanFull(),
                Card::make(
                    [
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('price')
                            ->label('Harga')
                            ->minValue(1)
                            ->numeric()
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)
                                    ->decimalSeparator('.')
                                    ->integer()
                                    ->mapToDecimalSeparator(['.'])
                                    ->minValue(1)
                                    ->normalizeZeros()
                                    ->padFractionalZeros()
                                    ->thousandsSeparator('.'),
                            )
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                    ]
                )->columnSpanFull()->columns(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('price')
                    ->sortable()
                    ->searchable()
                    ->money(currency: 'IDR', shouldConvert: true)
                    ->label('Harga'),
                TextColumn::make('stock')
                    ->sortable()
                    ->label('Stok'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->hidden(),
                TextColumn::make('description')
                    ->limit(20)
                    ->searchable()
                    ->label('Deskripsi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted_at', '=', null);
    }
}
