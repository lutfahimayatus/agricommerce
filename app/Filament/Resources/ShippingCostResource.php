<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingCostResource\Pages;
use App\Models\ShippingCost;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShippingCostResource extends Resource
{
    protected static ?string $model = ShippingCost::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getModelLabel(): string
    {
        return 'data biaya ongkir';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Biaya Ongkir';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('province_code')
                        ->relationship('province', 'name')
                        ->label('Provinsi')
                        ->required()
                        ->afterStateUpdated(fn (callable $set) => $set('city_code', null))
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => ucwords(strtolower($record->name)))
                        ->reactive()
                        ->required(),
                    Select::make('city_code')
                        ->relationship(
                            'city',
                            'name',
                            fn (Builder $query, callable $get) => $query->where('province_code', '=', $get('province_code'))
                        )
                        ->label('Kabupaten/kota')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => ucwords(strtolower($record->name)))
                        ->preload()
                        ->searchable()
                        ->required(),
                    TextInput::make('cost')
                        ->label('Biaya ongkir')
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
                        ->columnSpanFull(),

                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('province.name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing((fn (string $state): string => ucwords(strtolower($state))))
                    ->label('Provinsi'),
                TextColumn::make('city.name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing((fn (string $state): string => ucwords(strtolower($state))))
                    ->label('Kabupaten/Kota'),
                TextColumn::make('cost')
                    ->sortable()
                    ->money('IDR', true)
                    ->label('Biaya Ongkir'),
                TextColumn::make('id')
                    ->sortable()
                    ->hidden(),

            ])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ManageShippingCosts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted_at', '=', null);
    }
}
