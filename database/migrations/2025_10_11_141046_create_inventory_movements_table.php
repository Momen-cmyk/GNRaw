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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');

            // Movement Details
            $table->enum('movement_type', [
                'purchase',      // Stock received from supplier
                'sale',          // Stock sold to customer
                'return',        // Customer return
                'adjustment',    // Manual adjustment
                'damage',        // Damaged goods
                'expired',       // Expired products
                'transfer',      // Transfer between locations
                'reserve',       // Reserved for order
                'release',       // Released from reserve
            ]);

            $table->integer('quantity');
            $table->integer('quantity_before');
            $table->integer('quantity_after');

            // Reference
            $table->string('reference_type')->nullable(); // Order, PurchaseOrder, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related record
            $table->string('reference_number')->nullable(); // Order number, etc.

            // Details
            $table->string('batch_number')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_value', 10, 2)->nullable();

            // User who made the movement
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable(); // admin, supplier, system

            // Location
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('supplier_products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            // Indexes
            $table->index('inventory_id');
            $table->index('product_id');
            $table->index('movement_type');
            $table->index('created_at');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
