<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'total_pay' => $this->total_pay,
            'shipping_cost' => $this->total_pay - $this->details->sum('total_price'),
        ];
    }
}
