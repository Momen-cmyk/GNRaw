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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');

            // Product Details (snapshot at time of order)
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->text('product_description')->nullable();

            // Pricing
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Specifications
            $table->string('unit_of_measure')->nullable();
            $table->text('specifications')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('supplier_products')->onDelete('cascade');

            // Indexes
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
