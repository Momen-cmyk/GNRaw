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
        // Add approval status to suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('admins');
        });

        // Create supplier documents table
        Schema::create('supplier_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('document_type'); // ISO, GMP, WC, Manufacturing_Certificate (COA is per product)
            $table->string('document_name');
            $table->string('file_path');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('admins');
        });

        // Enhance existing supplier products table
        Schema::table('supplier_products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('unit_of_measure')->nullable();
            $table->integer('minimum_order_quantity')->default(1);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_updated')->nullable();

            $table->foreign('approved_by')->references('id')->on('admins');
        });

        // Create product documents table
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('document_type'); // COA, Specification, Certificate
            $table->string('document_name');
            $table->string('file_path');
            $table->boolean('is_verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('supplier_products')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('admins');
        });

        // Create client inquiries table
        Schema::create('client_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('inquiry_type', ['general', 'product_quote', 'custom_manufacturing']);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->enum('status', ['new', 'in_progress', 'quoted', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('supplier_products');
            $table->foreign('assigned_to')->references('id')->on('admins');
        });

        // Create public products table (curated selection)
        Schema::create('public_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_product_id');
            $table->string('display_name');
            $table->text('short_description');
            $table->text('full_description');
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('supplier_product_id')->references('id')->on('supplier_products');
        });

        // Create product categories table
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('public_products');
        Schema::dropIfExists('client_inquiries');
        Schema::dropIfExists('product_documents');
        Schema::dropIfExists('supplier_products');
        Schema::dropIfExists('supplier_documents');

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approval_status', 'rejection_reason', 'approved_at', 'approved_by']);
        });
    }
};
