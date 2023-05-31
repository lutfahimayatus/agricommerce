<?php

namespace App\Filament\Resources\ShippingCostResource\Pages;

use App\Filament\Resources\ShippingCostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingCost extends EditRecord
{
    protected static string $resource = ShippingCostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
