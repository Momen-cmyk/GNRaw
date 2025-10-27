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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('type'); // comment_added, product_approved, product_rejected, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like product_id, comment_id, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['supplier_id', 'is_read']);
            $table->index(['supplier_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
