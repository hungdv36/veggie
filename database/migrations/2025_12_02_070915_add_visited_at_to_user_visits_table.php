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
        $table->timestamp('visited_at')->nullable()->after('user_agent');
    });
}

public function down()
{
    Schema::table('user_visits', function (Blueprint $table) {
        $table->dropColumn('visited_at');
    });
}

};
