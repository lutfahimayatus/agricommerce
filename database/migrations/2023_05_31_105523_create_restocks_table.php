<?php

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->date('restock_date');
            $table->foreignIdFor(Supplier::class);
            $table->foreignIdFor(Product::class);
            $table->unsignedMediumInteger('amount');
            $table->unsignedBigInteger('buying_price');
            $table->unsignedBigInteger('selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restocks');
    }
};
