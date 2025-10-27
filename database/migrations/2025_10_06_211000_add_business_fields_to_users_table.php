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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('bio');
            $table->string('company_activity')->nullable()->after('company_name');
            $table->string('partnership_name')->nullable()->after('company_activity');
            $table->string('business_type')->nullable()->after('partnership_name');
            $table->text('company_description')->nullable()->after('business_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_activity',
                'partnership_name',
                'business_type',
                'company_description'
            ]);
        });
    }
};
