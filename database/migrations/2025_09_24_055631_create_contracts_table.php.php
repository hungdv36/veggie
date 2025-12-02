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
        Schema::create(table: 'contacts', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'full_name');
            $table->string(column: 'phone_number')->nullable();
            $table->string(column: 'email')->nullable();
            $table->string(column: 'message');
            $table->boolean(column: 'is_addressed')->default(value: false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'contacts');
    }
};
