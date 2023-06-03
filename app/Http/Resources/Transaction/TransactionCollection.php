<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn ($transaction) => [
                'id' => $transaction->id,
                'city' => ucwords(strtolower($transaction->shippingCost->city->name)),
                'status' => $transaction->status,
                'tracking_number' => $transaction->tracking_number,
                'total_pay' => $transaction->total_pay,
            ]
        )->toArray();
    }
}
