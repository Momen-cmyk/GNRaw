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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('type'); // product_added, product_updated, product_deleted, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like product_id, supplier_id, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['admin_id', 'is_read']);
            $table->index(['admin_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
