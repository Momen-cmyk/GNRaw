# Quick Fix: Profile Picture Upload on Hostinger

## Problem

Profile picture shows "updated successfully" but image doesn't change.

## Immediate Solution (3 Steps)

### Step 1: Upload Test Script

Upload `test_upload_hostinger.php` to your Hostinger root directory and access it:

```
https://yoursite.com/test_upload_hostinger.php
```

Test if basic file uploads work. If they don't, fix permissions first.

### Step 2: Fix Directory Permissions

Via SSH or Hostinger File Manager:

```bash
cd /home/u528572921/public_html
mkdir -p public/images/admins
chmod -R 755 public/images/
chmod 755 public/images/admins
```

**Or via File Manager:**

1. Navigate to `public_html/public/images/`
2. Create folder: `admins`
3. Right-click → Permissions → Set to `755`

### Step 3: Upload Updated Files

Upload these updated files to Hostinger:

-   `app/Http/Controllers/AdminController.php` (simplified upload logic)
-   `app/Models/Admin.php` (fixed picture accessor)

## How It Works Now

The code has been simplified to:

1. ✅ Upload directly to `public/images/admins/` (no storage link needed)
2. ✅ Delete old picture automatically
3. ✅ Save relative path to database
4. ✅ Display using direct asset URLs

**File Path**: `public/images/admins/admin_1_timestamp.jpg`  
**Database**: `images/admins/admin_1_timestamp.jpg`  
**URL**: `https://yoursite.com/images/admins/admin_1_timestamp.jpg`

## Testing

### 1. Check Permissions

```bash
ls -la public/images/
# Should show: drwxr-xr-x (755)
```

### 2. Test Upload

1. Login to admin panel
2. Go to Profile
3. Click "Change Profile Picture"
4. Upload an image
5. Check browser console for errors
6. Check if image appears immediately

### 3. Verify File Created

Check if file exists:

```bash
ls -la public/images/admins/
# Should show your uploaded file
```

### 4. Check Database

```sql
SELECT id, name, picture FROM admins WHERE id = 1;
-- picture should be: images/admins/admin_1_timestamp.jpg
```

## Common Issues & Fixes

### Issue 1: "Failed to create directory"

**Fix:**

```bash
mkdir -p public/images/admins
chmod 755 public/images/admins
```

### Issue 2: "Failed to move uploaded file"

**Fix:**

```bash
chmod 777 public/images/admins  # Temporary
# Upload test file
chmod 755 public/images/admins  # Set back to secure
```

### Issue 3: Image doesn't display after upload

**Check:**

1. File exists: `public/images/admins/filename.jpg`
2. Database value: Should be `images/admins/filename.jpg` (not `storage/...`)
3. URL works: Open `https://yoursite.com/images/admins/filename.jpg` directly

### Issue 4: Old image still showing

**Fix:** Clear browser cache or use Ctrl+F5

## Manual Database Fix (if needed)

If old images have wrong paths in database:

```sql
-- Check current paths
SELECT id, name, picture FROM admins;

-- Fix paths (remove storage/ prefix)
UPDATE admins
SET picture = REPLACE(picture, 'storage/', '')
WHERE picture LIKE 'storage/%';

-- Fix paths (ensure they start with images/)
UPDATE admins
SET picture = CONCAT('images/admins/', SUBSTRING_INDEX(picture, '/', -1))
WHERE picture NOT LIKE 'images/%' AND picture IS NOT NULL;
```

## Debugging Steps

### 1. Enable Laravel Debug Mode

In `.env`:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### 2. Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

### 3. Check PHP Error Log

Via Hostinger File Manager:

-   Navigate to `public_html/`
-   Look for `error_log` file
-   Download and check for errors

### 4. Test with Browser Console

1. Open browser DevTools (F12)
2. Go to Network tab
3. Try uploading profile picture
4. Check if upload request succeeds (status 200)
5. Check response JSON

## Expected Response

Successful upload should return:

```json
{
    "status": 1,
    "image_path": "https://yoursite.com/images/admins/admin_1_1234567890.jpg",
    "message": "Profile picture updated successfully."
}
```

Failed upload should return:

```json
{
    "status": 0,
    "message": "Failed to upload profile picture. Error: [specific error]"
}
```

## Still Not Working?

### Option 1: Use Test Script

1. Access `https://yoursite.com/test_upload_hostinger.php`
2. Try uploading a test image
3. If it works, the problem is in Laravel code
4. If it doesn't, it's a server/permissions issue

### Option 2: Check PHP Settings

Create `info.php`:

```php
<?php phpinfo(); ?>
```

Upload to public folder and check:

-   `upload_max_filesize`
-   `post_max_size`
-   `file_uploads` (should be On)

### Option 3: Contact Support

If nothing works, provide Hostinger support with:

1. Error message from Laravel logs
2. Error message from PHP error_log
3. Result of test_upload_hostinger.php
4. Directory permissions screenshot

## Prevention

Add to deployment checklist:

```bash
# Always run after deploying to Hostinger
mkdir -p public/images/admins
chmod -R 755 public/images/
php artisan config:clear
php artisan cache:clear
```

## Success Indicators

✅ Directory `public/images/admins/` exists with 755 permissions  
✅ Test upload script works  
✅ Profile picture upload returns success JSON  
✅ Image file appears in directory immediately  
✅ Image displays on profile page without refresh  
✅ Database contains correct path (`images/admins/filename.jpg`)  
✅ Direct URL to image works
