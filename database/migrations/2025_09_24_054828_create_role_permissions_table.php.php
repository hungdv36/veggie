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
        Schema::create(table: 'role_permission', callback: function (Blueprint $table): void {
            $table->foreignId(column: 'role_id')->constrained(table: 'roles')->onDelete(action: 'cascade');
            $table->foreignId(column: 'permission_id')->constrained(table: 'permissions')->onDelete(action: 'cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'role_permission');
    }
};
