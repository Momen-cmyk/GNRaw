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
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('admin_id');
            $table->text('comment');
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_read_by_supplier')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('supplier_products')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['product_id', 'created_at']);
            $table->index(['admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
};
