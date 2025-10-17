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
        Schema::create(table: 'order_status_history', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'role_id')->constrained(table: 'roles')->onDelete(action: 'cascade');
            $table->enum(column: 'status', allowed: ['pending', 'processing', 'shipped', 'completed', 'cancelled']);
            $table->timestamp(column: 'changed_at')->useCurrent();
            $table->text(column: 'notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'order_status_history');
    }
};
