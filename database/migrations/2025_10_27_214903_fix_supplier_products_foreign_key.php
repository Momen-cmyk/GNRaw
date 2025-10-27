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
            // Drop the old foreign key that references users
            $table->dropForeign(['supplier_id']);

            // Add new foreign key that references suppliers table
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_products', function (Blueprint $table) {
            // Drop the suppliers foreign key
            $table->dropForeign(['supplier_id']);

            // Re-add the users foreign key (for rollback)
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
