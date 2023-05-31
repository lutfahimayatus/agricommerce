<?php

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
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost');
            $table->char('province_code', 2);
            $table->char('city_code', 4);

            $table->foreign('province_code')
                ->references('code')
                ->on('provinces')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('city_code')
                ->references('code')
                ->on('cities')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_costs');
    }
};
