<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('qty')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk'),
                TextColumn::make('qty')
                    ->label('QTY'),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', true),
            ]);
    }
}
