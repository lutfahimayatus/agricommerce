<?php

namespace App\Filament\Resources\ShippingCostResource\Pages;

use App\Filament\Resources\ShippingCostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShippingCosts extends ManageRecords
{
    protected static string $resource = ShippingCostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
