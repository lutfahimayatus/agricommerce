<?php

use App\Models\ShippingCost;
use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(ShippingCost::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedBigInteger('total_pay');
            $table->string('proof_of_transaction')->nullable();
            $table->enum('status', ['SUCCESS', 'NOT_PAID', 'ERROR', 'PENDING'])
                ->default('NOT_PAID');
            $table->string('address');
            $table->string('tracking_number')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
