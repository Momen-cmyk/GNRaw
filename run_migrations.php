<?php

/**
 * Migration Runner for Hostinger
 * Run this file by visiting: https://gnraw.com/run_migrations.php
 */

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "<h2>❌ Error: artisan file not found</h2>";
    echo "<p>Make sure this script is in the same directory as your Laravel project (where artisan file is located).</p>";
    echo "<p>Current directory: " . getcwd() . "</p>";
    echo "<p>Files in current directory:</p><ul>";
    foreach (scandir('.') as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>" . $file . "</li>";
        }
    }
    echo "</ul>";
    exit;
}

// Bootstrap Laravel
require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "<h2>🚀 Running Laravel Migrations...</h2>";
    echo "<pre>";

    // Run migrations
    $kernel->call('migrate', ['--force' => true]);

    echo "\n✅ Migrations completed successfully!\n";

    // Clear caches
    echo "\n🧹 Clearing caches...\n";
    $kernel->call('config:clear');
    $kernel->call('cache:clear');
    $kernel->call('view:clear');
    $kernel->call('route:clear');

    echo "\n✅ Caches cleared successfully!\n";

    // Check migration status
    echo "\n📊 Migration Status:\n";
    $kernel->call('migrate:status');

    echo "\n🎉 All done! Your website should work now.\n";
    echo "</pre>";

    echo "<p><strong>Next steps:</strong></p>";
    echo "<ul>";
    echo "<li>Change APP_DEBUG=false in your .env file</li>";
    echo "<li>Test your website: <a href='https://gnraw.com'>https://gnraw.com</a></li>";
    echo "<li>Delete this file for security</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<h2>❌ Error occurred:</h2>";
    echo "<pre>";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";

    echo "<p><strong>Troubleshooting:</strong></p>";
    echo "<ul>";
    echo "<li>Make sure vendor/ folder exists</li>";
    echo "<li>Check if .env file is properly configured</li>";
    echo "<li>Verify database connection</li>";
    echo "</ul>";
}
