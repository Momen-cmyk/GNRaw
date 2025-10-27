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
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->text('specifications');
            $table->text('pharmacopeia_details');
            $table->string('product_category')->nullable();
            $table->string('cas_number')->nullable(); // Chemical Abstracts Service number
            $table->string('molecular_formula')->nullable();
            $table->string('molecular_weight')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive, pending_review
            $table->timestamps();

            $table->index(['supplier_id', 'status']);
            $table->index('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
