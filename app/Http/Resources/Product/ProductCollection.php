<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'picture' => asset('/storage/'.$product->picture),
                'price' => $product->price,
            ]
        )->toArray();
    }
}
