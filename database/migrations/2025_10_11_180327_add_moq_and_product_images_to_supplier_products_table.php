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
        Schema::table('supplier_products', function (Blueprint $table) {
            // Add MOQ field
            $table->decimal('moq', 10, 2)->nullable()->after('molecular_weight')->comment('Minimum Order Quantity in Kg');

            // Add product_images field to store array of image paths
            $table->json('product_images')->nullable()->after('description')->comment('Array of product image paths');

            // Remove pharmacopeia_details field as it's no longer needed
            $table->dropColumn('pharmacopeia_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_products', function (Blueprint $table) {
            // Remove the new fields
            $table->dropColumn(['moq', 'product_images']);

            // Add back pharmacopeia_details field
            $table->text('pharmacopeia_details')->nullable()->after('specifications');
        });
    }
};
