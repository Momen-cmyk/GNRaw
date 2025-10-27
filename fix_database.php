<?php

/**
 * Database Fix Script for Hostinger
 * This script will fix the missing database columns directly
 * Run this file by visiting: https://genuine-nutra.com/fix_database.php
 */

// Database configuration (update these with your actual values)
$host = '127.0.0.1';
$dbname = 'u528572921_gnraw_db';
$username = 'u528572921_gnraw_user';
$password = '7M:f=LH4Uj#K';

echo "<h2>🔧 Fixing Database Schema...</h2>";
echo "<pre>";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected to database successfully!\n\n";

    // Check if supplier_products table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'supplier_products'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Table 'supplier_products' does not exist!\n";
        echo "You need to run Laravel migrations first.\n";
        exit;
    }

    echo "✅ Table 'supplier_products' exists!\n\n";

    // Check current table structure
    echo "📊 Current table structure:\n";
    $stmt = $pdo->query("DESCRIBE supplier_products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    echo "\n";

    // Add missing columns
    $alterations = [
        "ALTER TABLE supplier_products ADD COLUMN is_approved TINYINT(1) DEFAULT 0",
        "ALTER TABLE supplier_products ADD COLUMN status VARCHAR(20) DEFAULT 'active'",
        "ALTER TABLE supplier_products ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL"
    ];

    foreach ($alterations as $sql) {
        try {
            $pdo->exec($sql);
            echo "✅ Executed: " . $sql . "\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "⚠️  Column already exists: " . $sql . "\n";
            } else {
                echo "❌ Error: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\n✅ Database schema fixed!\n\n";

    // Verify the changes
    echo "📊 Updated table structure:\n";
    $stmt = $pdo->query("DESCRIBE supplier_products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }

    echo "\n🎉 Database fix completed successfully!\n";
    echo "Your website should work now.\n";
} catch (PDOException $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Check your database credentials in this script\n";
    echo "2. Make sure the database exists\n";
    echo "3. Verify the user has proper permissions\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li>Change APP_DEBUG=false in your .env file</li>";
echo "<li>Test your website: <a href='https://genuine-nutra.com'>https://genuine-nutra.com</a></li>";
echo "<li>Delete this file for security</li>";
echo "</ul>";
