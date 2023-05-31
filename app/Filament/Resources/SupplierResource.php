<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Ysfkaya\FilamentPhoneInput\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'Data Supplier';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Supplier';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required(),
                    TextInput::make('email')
                        ->required()
                        ->email(),
                    PhoneInput::make('phone_number')
                        ->required()
                        ->placeholder('0812345678910')
                        ->formatOnDisplay(true)
                        ->autoPlaceholder('polite')
                        ->initialCountry('id')
                        ->displayNumberFormat(PhoneInputNumberType::E164)
                        ->focusNumberFormat(PhoneInputNumberType::E164)
                        ->disallowDropdown()
                        ->label('No. telepon'),
                    TextInput::make('address')
                        ->label('Alamat')
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('phone_number')
                    ->searchable()
                    ->label('No. Telepon'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Alamat')
                    ->limit(25),
                TextColumn::make('id')
                    ->sortable()
                    ->hidden(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('deleted_at', '=', null);
    }
}
