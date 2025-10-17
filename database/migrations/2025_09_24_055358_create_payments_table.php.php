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
        Schema::create(table: 'payments', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'order_id')->constrained(table: 'orders')->onDelete(action: 'cascade');
            $table->enum(column: 'payment_method', allowed: ['cash', 'paypal']);
            $table->string(column: 'transaction_id')->nullable();
            $table->decimal(column: 'amount', total: 10, places: 2);
            $table->enum(column: 'status', allowed: ['pending', 'completed', 'failed'])->default(value: 'pending');
            $table->timestamp(column: 'paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'payments');
    }
};
