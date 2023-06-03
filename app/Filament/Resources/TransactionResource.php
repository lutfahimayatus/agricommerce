<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers\DetailsRelationManager;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';

    protected static ?string $navigationGroup = 'Manajemen Transaksi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getModelLabel(): string
    {
        return 'data transaksi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Transaksi';
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema(
                        [
                            Card::make(
                                [
                                    FileUpload::make('proof_of_transaction')
                                        ->image()
                                        ->imagePreviewHeight('500')
                                        ->panelLayout('integrated')
                                        ->label('Bukti Transaksi')
                                        ->disabled()
                                        ->enableOpen()
                                        ->enableDownload()
                                        ->directory('proofs_of_transaction')
                                        ->maxSize(5000),
                                    Fieldset::make('Informasi Pembeli')
                                        ->schema([
                                            Placeholder::make('user.name')
                                                ->label('Nama')
                                                ->content(fn ($record) => $record->user->name),
                                            Placeholder::make('user.address')
                                                ->label('Alamat')
                                                ->content(fn ($record) => $record->address),
                                            Placeholder::make('created_at')
                                                ->label('Tgl Transaksi')
                                                ->content(fn ($record) => Carbon::parse($record->created_at)->toDateString()),
                                            Placeholder::make('total_item')
                                                ->label('Jumlah Item')
                                                ->content(fn ($record) => $record->details->sum('qty')),
                                            Placeholder::make('total_price')
                                                ->label('Total Pembelian')
                                                ->content(fn ($record) => money($record->total_pay, 'IDR', true)),
                                        ]),
                                ]
                            )->columnSpan([
                                'lg' => 2,
                            ]),
                            Grid::make()->schema(
                                [
                                    Card::make()
                                        ->schema(
                                            [
                                                Fieldset::make('Pembayaran')
                                                    ->schema([
                                                        Select::make('status')
                                                            ->options([
                                                                'PENDING' => 'Pending',
                                                                'NOT_PAID' => 'Belum Dibayar',
                                                                'ERROR' => 'Error',
                                                                'SUCCESS' => 'Selesai',
                                                            ])->columnSpanFull(),
                                                    ]),
                                            ]
                                        ),
                                    Card::make()
                                        ->schema([
                                            Fieldset::make('Pengiriman')
                                                ->schema([
                                                    Placeholder::make('shipping_cost')
                                                        ->label('Biaya Ongkir')
                                                        ->columnSpanFull()
                                                        ->content(fn ($record) => money($record->total_pay - $record->details->sum('total_price'), 'IDR', true)),
                                                    Placeholder::make('province')
                                                        ->label('Provinsi')
                                                        ->columnSpanFull()
                                                        ->content(fn ($record) => ucwords(strtolower($record->shippingCost->province->name))),
                                                    Placeholder::make('city')
                                                        ->label('Kota/Kabupaten')
                                                        ->columnSpanFull()
                                                        ->content(fn ($record) => ucwords(strtolower($record->shippingCost->city->name))),
                                                    TextInput::make('tracking_number')
                                                        ->label('No. resi')
                                                        ->columnSpanFull(),
                                                ]),
                                        ]),
                                ]
                            )->columnSpan([
                                'lg' => 1,
                            ]),
                        ]
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pembeli')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tgl Transaksi')
                    ->date()
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('status')
                    ->sortable()
                    ->enum([
                        'PENDING' => 'Pending',
                        'NOT_PAID' => 'Belum Dibayar',
                        'ERROR' => 'Error',
                        'SUCCESS' => 'Selesai',
                    ])->colors([
                        'warning' => 'NOT_PAID',
                        'success' => 'SUCCESS',
                        'danger' => 'ERROR',
                    ]),

                TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable(),
                TextColumn::make('total_pay')
                    ->label('Total bayar')
                    ->sortable()
                    ->money('IDR', true),

            ])->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
