<?php

namespace App\Http\Resources\Shipping;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShippingCostCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn ($shippingCost) => [
                'id' => $shippingCost->id,
                'province' => ucwords(strtolower($shippingCost->province->name)),
                'city' => ucwords(strtolower($shippingCost->city->name)),
                'cost' => $shippingCost->cost,
            ]
        )->toArray();
    }
}
