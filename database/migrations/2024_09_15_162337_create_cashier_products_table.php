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
        Schema::create('cashier_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Kasir
            $table->unsignedBigInteger('product_id'); // Produk
            $table->unsignedBigInteger('flavor_id')->nullable(); // Varian rasa (opsional)
            $table->bigInteger('stock'); // Stok produk yang dimiliki kasir

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
        Schema::dropIfExists('cashier_products');
    }
};
