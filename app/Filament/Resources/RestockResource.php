<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestockResource\Pages;
use App\Models\Restock;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class RestockResource extends Resource
{
    protected static ?string $model = Restock::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-in';

    protected static ?string $navigationGroup = 'Manajemen Transaksi';

    public static function getModelLabel(): string
    {
        return 'data restok';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Restok';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->label('Supplier')
                        ->preload()
                        ->required(),
                    Select::make('product_id')
                        ->relationship('product', 'name')
                        ->label('Produk')
                        ->searchable()
                        ->preload()
                        ->required(),
                    DatePicker::make('restock_date')
                        ->label('Tanggal restok')
                        ->default(now())
                        ->required()
                        ->maxDate(now()),
                    TextInput::make('amount')
                        ->label('Jumlah')
                        ->minValue(1)
                        ->required()
                        ->numeric(),
                    TextInput::make('buying_price')
                        ->label('Harga beli')
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
                        ->required(),
                    TextInput::make('selling_price')
                        ->label('Harga jual')
                        ->minValue(1)
                        ->gt('buying_price')
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
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable()
                    ->label('Produk'),
                TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable()
                    ->label('Supplier'),
                TextColumn::make('restock_date')
                    ->date()
                    ->sortable()
                    ->label('Tgl Restok'),
                TextColumn::make('amount')
                    ->sortable()
                    ->label('Jumlah'),
                TextColumn::make('buying_price')
                    ->sortable()
                    ->money('IDR', true)
                    ->label('Harga Beli'),
                TextColumn::make('selling_price')
                    ->sortable()
                    ->money('IDR', true)
                    ->label('Harga Jual'),
                TextColumn::make('id')
                    ->sortable()
                    ->hidden(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRestocks::route('/'),
        ];
    }
}
