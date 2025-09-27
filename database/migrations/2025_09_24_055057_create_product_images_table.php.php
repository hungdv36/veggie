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
        Schema::create(table: 'product_images', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'product_id')->constrained(table: 'products')->onDelete(action: 'cascade');
            $table->string(column: 'image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'product_images');
    }
};
