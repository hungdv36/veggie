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
        Schema::create(table: 'reviews', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'role_id')->constrained(table: 'roles')->onDelete(action: 'cascade');
            $table->foreignId(column: 'product_id')->constrained(table: 'products')->onDelete(action: 'cascade');
            $table->tinyInteger(column: 'rating')->unsigned();
            $table->string(column: 'comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'reviews');
    }
};
