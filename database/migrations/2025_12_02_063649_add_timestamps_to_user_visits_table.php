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
        $table->timestamps(); // thêm created_at và updated_at
    });
}

public function down()
{
    Schema::table('user_visits', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}

};
