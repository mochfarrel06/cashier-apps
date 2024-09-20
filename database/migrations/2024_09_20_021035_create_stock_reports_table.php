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
            $table->unsignedBigInteger('user_id'); // ID kasir
            $table->unsignedBigInteger('product_id'); // ID produk
            $table->unsignedBigInteger('flavor_id')->nullable(); // ID varian rasa
            $table->date('report_date'); // Tanggal laporan
            $table->bigInteger('stock'); // Stok yang tersedia pada tanggal laporan

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('flavor_id')->references('id')->on('flavors')->onDelete('cascade');
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
