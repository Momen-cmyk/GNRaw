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
        Schema::create('customer_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('supplier_products')->onDelete('set null');
            $table->string('product_name');
            $table->text('message');
            $table->string('status')->default('pending'); // pending, responded, closed
            $table->text('admin_notes')->nullable();
            $table->boolean('is_public')->default(false); // Whether product should be made public
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['supplier_id', 'status']);
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_inquiries');
    }
};
