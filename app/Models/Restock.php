<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Restock extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    protected static function booted(): void
    {
        static::created(function (Restock $restock) {
            $product = $restock->product;
            $restock->product()->update(
                [
                    'stock' => $product->stock + $restock->amount
                ]
            );
        });

        static::updating(function (Restock $restock) {
            $prevStock = $restock->getOriginal('amount');
            $stock = $restock->product->stock;
            $currentStock = ($stock - $prevStock) + $restock->amount;
            $restock->product()->update(
                [
                    'stock' => $currentStock
                ]
            );
        });
    }
}
