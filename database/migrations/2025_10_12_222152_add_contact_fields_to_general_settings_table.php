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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('site_address')->nullable()->after('site_phone');
            $table->string('site_city')->nullable()->after('site_address');
            $table->string('site_state')->nullable()->after('site_city');
            $table->string('site_country')->nullable()->after('site_state');
            $table->string('site_zip_code')->nullable()->after('site_country');
            $table->string('site_website')->nullable()->after('site_zip_code');
            $table->text('site_description')->nullable()->after('site_website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_address',
                'site_city',
                'site_state',
                'site_country',
                'site_zip_code',
                'site_website',
                'site_description'
            ]);
        });
    }
};
