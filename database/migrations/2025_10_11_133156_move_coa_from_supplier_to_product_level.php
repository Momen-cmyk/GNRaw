<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration handles the transition of COA documents from supplier-level to product-level.
     * Since COA documents are now specific to individual products rather than suppliers,
     * we need to remove old supplier-level COA records. Suppliers will be notified to
     * upload COAs for each product individually.
     */
    public function up(): void
    {
        // Remove COA documents from supplier_documents table if it exists
        // (COAs are now stored in product_documents table instead)
        if (Schema::hasTable('supplier_documents')) {
            DB::table('supplier_documents')
                ->where('document_type', 'COA')
                ->delete();
        }

        // Also remove COA from supplier_certificates table if it exists
        if (Schema::hasTable('supplier_certificates')) {
            DB::table('supplier_certificates')
                ->where('certificate_type', 'coa')
                ->delete();
        }

        // Note: Suppliers will need to re-upload COAs for each product individually
        // through the product management interface. This is logged as a system change.
        \Log::info('Migration: Moved COA requirement from supplier-level to product-level. Suppliers need to upload COAs per product.');
    }

    /**
     * Reverse the migrations.
     *
     * Note: This migration is not fully reversible as we cannot restore
     * the previous COA documents without knowing which products they belonged to.
     */
    public function down(): void
    {
        // This migration is not fully reversible as we don't have enough information
        // to restore supplier-level COAs to their original state.
        // The table structure remains unchanged, so no schema changes to revert.
        \Log::warning('Migration rollback: COA documents were removed in the forward migration and cannot be automatically restored.');
    }
};
