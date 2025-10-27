<?php

/**
 * Fix Hostinger Storage Issues
 * This script fixes common storage issues on Hostinger hosting
 */

echo "=== Hostinger Storage Fix Script ===\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the Laravel root directory\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 1. Create storage directories if they don't exist
$storageDirs = [
    'storage/app/public',
    'storage/app/public/images',
    'storage/app/public/images/admins',
    'storage/app/public/images/suppliers',
    'storage/app/public/images/site',
    'storage/app/public/blog-posts',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
];

echo "\n📁 Creating storage directories...\n";
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created: $dir\n";
        } else {
            echo "❌ Failed to create: $dir\n";
        }
    } else {
        echo "✅ Exists: $dir\n";
    }
}

// 2. Set proper permissions
echo "\n🔐 Setting file permissions...\n";
$permissionDirs = [
    'storage',
    'bootstrap/cache',
    'public/storage'
];

foreach ($permissionDirs as $dir) {
    if (is_dir($dir)) {
        if (chmod($dir, 0755)) {
            echo "✅ Set permissions for: $dir\n";
        } else {
            echo "❌ Failed to set permissions for: $dir\n";
        }
    }
}

// 3. Create storage link
echo "\n🔗 Creating storage link...\n";
if (is_link('public/storage')) {
    echo "✅ Storage link already exists\n";
} else {
    if (symlink('../storage/app/public', 'public/storage')) {
        echo "✅ Storage link created successfully\n";
    } else {
        echo "❌ Failed to create storage link\n";
        echo "💡 Manual fix: Run 'php artisan storage:link' from SSH\n";
    }
}

// 4. Check .htaccess for storage
echo "\n📝 Checking .htaccess configuration...\n";
$htaccessPath = 'public/.htaccess';
if (file_exists($htaccessPath)) {
    $htaccessContent = file_get_contents($htaccessPath);
    if (strpos($htaccessContent, 'storage') !== false) {
        echo "✅ .htaccess contains storage rules\n";
    } else {
        echo "⚠️  .htaccess might need storage rules\n";
    }
} else {
    echo "❌ .htaccess file not found\n";
}

// 5. Test file upload permissions
echo "\n🧪 Testing file upload permissions...\n";
$testFile = 'storage/app/public/test_upload.txt';
$testContent = 'Test upload - ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "✅ File upload test successful\n";
    unlink($testFile); // Clean up test file
} else {
    echo "❌ File upload test failed\n";
}

// 6. Check environment configuration
echo "\n⚙️  Checking environment configuration...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_URL') !== false) {
        echo "✅ APP_URL configured\n";
    } else {
        echo "⚠️  APP_URL not found in .env\n";
    }
} else {
    echo "❌ .env file not found\n";
}

echo "\n=== Fix Complete ===\n";
echo "📋 Next steps:\n";
echo "1. Run: php artisan storage:link\n";
echo "2. Set proper file permissions: chmod -R 755 storage/\n";
echo "3. Check that public/storage link exists\n";
echo "4. Test profile picture upload again\n";
echo "\n💡 If issues persist, check Hostinger file manager permissions\n";
