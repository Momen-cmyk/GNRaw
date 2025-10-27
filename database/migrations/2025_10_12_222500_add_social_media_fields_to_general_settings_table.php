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
            $table->string('facebook_url')->nullable()->after('site_website');
            $table->string('twitter_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('twitter_url');
            $table->string('linkedin_url')->nullable()->after('instagram_url');
            $table->string('youtube_url')->nullable()->after('linkedin_url');
            $table->string('whatsapp_number')->nullable()->after('youtube_url');
            $table->string('telegram_username')->nullable()->after('whatsapp_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_url',
                'twitter_url',
                'instagram_url',
                'linkedin_url',
                'youtube_url',
                'whatsapp_number',
                'telegram_username'
            ]);
        });
    }
};
