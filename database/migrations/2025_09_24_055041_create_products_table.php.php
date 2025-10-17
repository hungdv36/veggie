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
        Schema::create(table: 'products', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'name');
            $table->string(column: 'slug')->unique();
            $table->foreignId(column: 'category_id')->constrained(table: 'categories')->onDelete(action: 'cascade');
            $table->text(column: 'description')->nullable();
            $table->decimal(column: 'price', total: 10, places: 2);
            $table->integer(column: 'stock')->default(value: 0);
            $table->string(column: 'status')->default(value: 'in_stock');
            $table->string(column: 'unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'products');
    }
};
