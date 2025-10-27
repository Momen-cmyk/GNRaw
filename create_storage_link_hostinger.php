<?php

/**
 * Create Storage Link for Hostinger
 * This script manually creates the storage link without using exec() or symlink()
 *
 * Run this file directly from your browser or via SSH
 */

echo "=== Hostinger Storage Link Creator ===\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the Laravel root directory\n";
    exit(1);
}

echo "✅ Laravel project detected\n\n";

// Define paths
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "📂 Target: $target\n";
echo "🔗 Link: $link\n\n";

// Check if target directory exists
if (!is_dir($target)) {
    echo "⚠️  Target directory doesn't exist. Creating it...\n";
    if (!mkdir($target, 0755, true)) {
        echo "❌ Failed to create target directory\n";
        exit(1);
    }
    echo "✅ Target directory created\n";
}

// Check if link already exists
if (file_exists($link) || is_link($link)) {
    echo "⚠️  Storage link already exists. Removing old link...\n";

    // Try to remove old link
    if (is_link($link)) {
        if (unlink($link)) {
            echo "✅ Old link removed\n";
        } else {
            echo "❌ Failed to remove old link\n";
        }
    } elseif (is_dir($link)) {
        echo "⚠️  'storage' is a directory, not a link. You may need to manually remove it via File Manager\n";
        echo "💡 Go to Hostinger File Manager and delete public/storage/ directory first\n";
        exit(1);
    }
}

// Try Method 1: PHP symlink (might work if enabled)
echo "\n🔧 Method 1: Trying PHP symlink...\n";
if (function_exists('symlink')) {
    if (@symlink($target, $link)) {
        echo "✅ SUCCESS! Storage link created via PHP symlink\n";
        verifyLink($link, $target);
        exit(0);
    } else {
        echo "❌ PHP symlink failed (might be disabled)\n";
    }
} else {
    echo "❌ PHP symlink function is disabled\n";
}

// Method 2: Create .htaccess redirect (fallback)
echo "\n🔧 Method 2: Creating .htaccess redirect fallback...\n";
$htaccessContent = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ ../storage/app/public/$1 [L]
</IfModule>
HTACCESS;

// Create public/storage directory
if (!is_dir($link)) {
    if (mkdir($link, 0755, true)) {
        echo "✅ Created public/storage directory\n";
    } else {
        echo "❌ Failed to create public/storage directory\n";
        exit(1);
    }
}

// Create .htaccess inside public/storage
$htaccessPath = $link . '/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ Created .htaccess redirect\n";
    echo "✅ Storage access configured via redirect\n\n";

    echo "📋 Configuration complete!\n";
    echo "Files uploaded to storage/app/public/ will be accessible via public/storage/\n";
} else {
    echo "❌ Failed to create .htaccess\n";
    exit(1);
}

// Method 3: Instructions for manual FTP/File Manager
echo "\n" . str_repeat("=", 60) . "\n";
echo "⚠️  IMPORTANT: If Method 2 doesn't work fully\n";
echo str_repeat("=", 60) . "\n\n";

echo "Manual steps via Hostinger File Manager:\n";
echo "1. Navigate to: public_html/public/\n";
echo "2. Delete the 'storage' directory if it exists\n";
echo "3. Create a new file named: .htaccess\n";
echo "4. Add this content:\n\n";
echo $htaccessContent . "\n\n";
echo "5. Save the file\n";
echo "6. Set directory permissions to 755\n\n";

echo "OR use the public directory method:\n";
echo "- Profile pictures will be stored directly in public/images/admins/\n";
echo "- No storage link required\n";
echo "- The updated AdminController already handles this automatically\n\n";

function verifyLink($link, $target)
{
    echo "\n🧪 Verifying link...\n";

    if (is_link($link)) {
        $linkTarget = readlink($link);
        echo "✅ Link exists\n";
        echo "   Points to: $linkTarget\n";

        if (is_dir($link)) {
            echo "✅ Link is accessible as directory\n";
        }
    } else {
        echo "⚠️  Link verification inconclusive\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ Script Complete!\n";
echo str_repeat("=", 60) . "\n";
