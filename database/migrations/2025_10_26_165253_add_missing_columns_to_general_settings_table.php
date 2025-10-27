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
            $table->string('site_address')->nullable();
            $table->string('site_city')->nullable();
            $table->string('site_state')->nullable();
            $table->string('site_country')->nullable();
            $table->string('site_zip_code')->nullable();
            $table->string('site_website')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('telegram_username')->nullable();
            $table->text('site_description')->nullable();
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
                'facebook_url',
                'twitter_url',
                'instagram_url',
                'linkedin_url',
                'youtube_url',
                'whatsapp_number',
                'telegram_username',
                'site_description'
            ]);
        });
    }
};
