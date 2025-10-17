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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'amount'])->default('amount'); // loại giảm giá
            $table->decimal('value', 10, 2); // giá trị giảm
            $table->decimal('min_order', 12, 2)->default(0); // đơn hàng tối thiểu
            $table->integer('usage_limit')->nullable(); // số lần sử dụng tối đa
            $table->integer('used')->default(0); // số lần đã dùng
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('status')->default(true); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
