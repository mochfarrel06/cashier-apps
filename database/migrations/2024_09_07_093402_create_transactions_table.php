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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->date('transaction_date');
            $table->string('transaction_number')->unique();
            $table->decimal('total', 20, 2);
            $table->string('payment_type');
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('paid_amount', 20, 2); // Jumlah yang dibayarkan
            $table->decimal('change_amount', 20, 2); // Kembalian

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
