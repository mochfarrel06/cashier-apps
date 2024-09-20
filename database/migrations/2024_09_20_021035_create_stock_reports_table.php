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
        Schema::create('stock_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_product_id'); // ID produk yang terkait
            $table->date('stock_date'); // Tanggal pencatatan stok
            $table->bigInteger('stock_in')->default(0); // Jumlah stok masuk
            $table->bigInteger('stock_out')->default(0); // Jumlah stok keluar
            $table->bigInteger('current_stock')->default(0); // Stok saat ini
            $table->timestamps();

            $table->foreign('cashier_product_id')->references('id')->on('cashier_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_reports');
    }
};
