# Hostinger Storage Fix Guide

## Problem

Profile picture upload shows "Profile picture updated successfully" but the image doesn't change on Hostinger hosting.

## Root Causes

1. **Storage Link Missing**: Laravel storage link not created
2. **File Permissions**: Incorrect permissions on storage directories
3. **Storage Configuration**: Hostinger's file system limitations
4. **Path Issues**: Asset paths not resolving correctly

## Solutions

### 1. Run the Storage Fix Script

```bash
php fix_hostinger_storage.php
```

### 2. Manual Steps (if script doesn't work)

#### A. Create Storage Link

**If you get "Call to undefined function exec()" error:**

Hostinger has disabled the `exec()` function for security. Use this alternative:

```bash
# Run the custom storage link script
php create_storage_link_hostinger.php
```

**Or try this (might not work on all Hostinger plans):**

```bash
php artisan storage:link
```

#### B. Set File Permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/storage/
```

#### C. Create Required Directories

```bash
mkdir -p storage/app/public/images/admins
mkdir -p storage/app/public/images/suppliers
mkdir -p storage/app/public/images/site
mkdir -p storage/app/public/blog-posts
```

#### D. Set Directory Permissions

```bash
chmod -R 755 storage/app/public/
```

### 3. Code Improvements Made

#### A. Enhanced Upload Method

-   Added fallback mechanism for Hostinger
-   Tries storage first, then public directory
-   Better error handling and reporting

#### B. Updated Admin Model

-   Handles both storage and public paths
-   Automatic path detection and resolution

### 4. Hostinger-Specific Fixes

#### A. Check .htaccess

Ensure your `public/.htaccess` includes:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^storage/(.*)$ storage/app/public/$1 [L]
</IfModule>
```

#### B. File Manager Permissions

1. Login to Hostinger File Manager
2. Navigate to your domain folder
3. Right-click on `storage` folder
4. Set permissions to `755`
5. Do the same for `public/storage` if it exists

#### C. Check Storage Link

In File Manager, verify that `public/storage` is a symbolic link pointing to `../storage/app/public`

### 5. Testing the Fix

#### A. Test File Upload

1. Go to Admin Panel → Profile
2. Try uploading a profile picture
3. Check if the image appears immediately
4. Check browser network tab for any 404 errors

#### B. Verify File Creation

1. Check `storage/app/public/images/admins/` directory
2. Look for files with names like `admin_1_timestamp.jpg`
3. Verify files are accessible via URL

### 6. Alternative Solution (If Storage Still Fails)

If the storage method still doesn't work, the code will automatically fall back to direct public directory upload:

-   Files will be stored in `public/images/admins/`
-   URLs will be `yoursite.com/images/admins/filename.jpg`
-   No storage link required for this method

### 7. Troubleshooting

#### A. Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

#### B. Check File Permissions

```bash
ls -la storage/
ls -la public/storage
```

#### C. Test Storage Link

```bash
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

#### D. exec() Function Disabled Error

**Error**: `Call to undefined function Illuminate\Filesystem\exec()`

**Cause**: Hostinger has disabled the `exec()` function for security

**Solution**:

1. Run the custom script: `php create_storage_link_hostinger.php`
2. Or manually create the redirect via File Manager (see script output)
3. Or use the public directory fallback (automatic in updated code)

### 8. Prevention

#### A. Add to Deployment Script

```bash
#!/bin/bash
# Add to your deployment script
php artisan storage:link
chmod -R 755 storage/
chmod -R 755 public/storage/
```

#### B. Environment Check

Add this to your `.env` for Hostinger:

```env
# Hostinger specific settings
FILESYSTEM_DISK=public
```

## Expected Results

After applying these fixes:

1. ✅ Profile picture uploads work immediately
2. ✅ Images are accessible via URL
3. ✅ No more "success but no change" issues
4. ✅ Both storage and public methods work
5. ✅ Proper error messages if upload fails

## Support

If issues persist:

1. Check Hostinger support documentation
2. Verify your hosting plan supports symbolic links
3. Contact Hostinger support for file permission issues
4. Consider using the public directory fallback method
