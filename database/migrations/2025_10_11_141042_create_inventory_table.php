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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');

            // Stock Information
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_reserved')->default(0); // Reserved for pending orders
            $table->integer('quantity_sold')->default(0);
            $table->integer('min_stock_level')->default(0); // For low stock alerts
            $table->integer('reorder_point')->default(0); // When to reorder
            $table->integer('max_stock_level')->nullable();

            // Pricing
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            // Location
            $table->string('warehouse_location')->nullable();
            $table->string('bin_location')->nullable();
            $table->string('shelf_number')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_discontinued')->default(false);
            $table->boolean('allow_backorder')->default(false);

            // Tracking
            $table->string('batch_number')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('product_id')->references('id')->on('supplier_products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            // Indexes
            $table->index(['product_id', 'supplier_id']);
            $table->index('quantity_available');
            $table->index('is_active');

            // Unique constraint - one inventory record per product per supplier
            $table->unique(['product_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
