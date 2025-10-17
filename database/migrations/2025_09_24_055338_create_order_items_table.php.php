<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Psy\Shell;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'order_items', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'order_id')->constrained(table: 'orders')->onDelete(action: 'cascade');
            $table->foreignId(column: 'product_id')->constrained(table: 'products')->onDelete(action: 'cascade');
            $table->integer(column: 'quantity');
            $table->decimal(column: 'price', total: 10, places: 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'order_items');
    }
};
