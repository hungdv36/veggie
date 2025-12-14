<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('reason')->nullable();
            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->enum('status', ['requested','reviewing','approved','rejected','done'])->default('requested');
            $table->text('staff_note')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
