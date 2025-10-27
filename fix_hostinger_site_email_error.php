<?php

/**
 * Fix for "Attempt to read property 'site_email' on null" error on Hostinger
 *
 * This script should be run on Hostinger after uploading the fixed files
 * to ensure the general_settings table has default data.
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\GeneralSetting;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Fixing site_email null error on Hostinger...\n";

try {
    // Check if general settings exist
    $settingsCount = GeneralSetting::count();
    echo "Current general settings count: $settingsCount\n";

    if ($settingsCount == 0) {
        echo "No general settings found. Creating default settings...\n";

        GeneralSetting::create([
            'site_title' => 'GNRAW',
            'site_email' => 'info@gnraw.com',
            'site_phone' => '+1-555-0123',
            'site_address' => 'Your Business Address',
            'site_city' => 'Your City',
            'site_state' => 'Your State',
            'site_country' => 'Your Country',
            'site_zip_code' => '12345',
            'site_website' => 'https://gnraw.com',
            'site_description' => 'Your trusted B2B platform for nutritional supplements',
            'site_meta_keywords' => 'nutritional supplements, B2B, suppliers, health products',
            'site_meta_description' => 'GNRAW - Your trusted B2B platform for nutritional supplements',
            'facebook_url' => null,
            'twitter_url' => null,
            'instagram_url' => null,
            'linkedin_url' => null,
            'youtube_url' => null,
            'whatsapp_number' => null,
            'telegram_username' => null,
            'site_logo' => null,
            'site_favicon' => null,
        ]);

        echo "Default general settings created successfully!\n";
    } else {
        echo "General settings already exist. No action needed.\n";
    }

    // Test the settings function
    echo "Testing settings function...\n";
    $settings = settings();
    if ($settings && isset($settings->site_email)) {
        echo "Settings function working correctly. site_email: " . $settings->site_email . "\n";
    } else {
        echo "ERROR: Settings function still returning null!\n";
    }

    echo "Fix completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
