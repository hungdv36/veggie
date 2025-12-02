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
    Schema::create('flash_sales', function (Blueprint $table) {
        $table->id();
        $table->timestamp('start_time')->nullable();
        $table->timestamp('end_time')->nullable();
        $table->integer('status')->default(1);
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('flash_sales');
}

};
