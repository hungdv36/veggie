<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->enum('status', [
                'requested',
                'reviewing',
                'approved',
                'rejected',
                'done',
                'packaging',
                'shipped_to_customer',
                'received_from_customer',
                'inspected',
                'completed_run' // đổi từ completed sang completed_run
            ])->default('requested')->change();
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->enum('status', [
                'requested',
                'reviewing',
                'approved',
                'rejected',
                'done',
                'packaging',
                'shipped_to_customer',
                'received_from_customer',
                'inspected',
                'completed' // rollback về completed
            ])->default('requested')->change();
        });
    }
};
