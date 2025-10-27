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
        Schema::create('supplier_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('certificate_type'); // iso, manufacturing, gmp, wc (COA is per product, not supplier)
            $table->string('certificate_name');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_size'); // in bytes
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('status')->default('active'); // active, expired, pending_review
            $table->timestamps();

            $table->index(['supplier_id', 'certificate_type']);
            $table->index(['supplier_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_certificates');
    }
};
