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
            if (!Schema::hasColumn('general_settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('site_website');
            }
            if (!Schema::hasColumn('general_settings', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('general_settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('general_settings', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('general_settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('general_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('general_settings', 'telegram_username')) {
                $table->string('telegram_username')->nullable()->after('whatsapp_number');
            }
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
