<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('user_visits', function (Blueprint $table) {
        // xoá cột sai
        $table->dropColumn(['visited_at']);

        // thêm cột device nếu chưa có
        if (!Schema::hasColumn('user_visits', 'device')) {
            $table->string('device')->nullable();
        }

        // bật timestamps nếu chưa có
        if (!Schema::hasColumn('user_visits', 'created_at')) {
            $table->timestamps();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_visits', function (Blueprint $table) {
            //
        });
    }
};
