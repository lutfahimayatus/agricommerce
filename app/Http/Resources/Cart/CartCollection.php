<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn ($cart) => [
                'id' => $cart->id,
                'product' => $cart->product->name,
                'price' => $cart->product->price,
                'picture' => asset('/storage/'.$cart->product->picture),
                'qty' => $cart->qty,
                'total' => $cart->qty * $cart->product->price,
            ]
        )->toArray();
    }
}
