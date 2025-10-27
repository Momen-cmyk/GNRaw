# Hostinger Upload Checklist for Genuine Nutra

## 📁 Files to Upload to Hostinger

### 1. **Core Application Files**

Upload these folders and files to `public_html/`:

-   ✅ `app/` (entire folder)
-   ✅ `bootstrap/` (entire folder)
-   ✅ `config/` (entire folder)
-   ✅ `database/` (entire folder)
-   ✅ `resources/` (entire folder)
-   ✅ `routes/` (entire folder)
-   ✅ `storage/` (entire folder)
-   ✅ `vendor/` (entire folder - or install via composer)
-   ✅ `artisan` (file)
-   ✅ `composer.json` (file)
-   ✅ `composer.lock` (file)
-   ✅ `package.json` (file)
-   ✅ `vite.config.js` (file)

### 2. **Public Files**

Upload these to `public_html/public/`:

-   ✅ `public/index.php`
-   ✅ `public/css/` (entire folder)
-   ✅ `public/js/` (entire folder)
-   ✅ `public/images/` (entire folder)
-   ✅ `public/vendors/` (entire folder)
-   ✅ `public/extra-assets/` (entire folder)
-   ✅ `public/favicon.ico`
-   ✅ `public/robots.txt`

### 3. **Configuration Files**

-   ✅ `.env` (create from `env_hostinger_gnraw.txt`)
-   ✅ `.htaccess` (create in `public_html/`)

## 🗄️ Database Setup in Hostinger

### 1. **Create Database**

1. Login to Hostinger hPanel
2. Go to **MySQL Databases**
3. Create database: `gnraw_db`
4. Create user: `gnraw_user`
5. Set strong password
6. Assign user to database

### 2. **Update .env File**

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=gnraw_db
DB_USERNAME=gnraw_user
DB_PASSWORD=your_database_password
```

## 📧 Email Setup in Hostinger

### 1. **Create Email Account**

1. Go to **Email Accounts** in hPanel
2. Create email: `info@gnraw.com`
3. Set strong password
4. Note down the password

### 2. **Update .env File**

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.gnraw.com
MAIL_PORT=587
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

## ⚙️ Server Configuration

### 1. **PHP Settings**

-   Set PHP version to **8.1 or higher**
-   Enable required extensions:
    -   OpenSSL
    -   PDO
    -   Mbstring
    -   Tokenizer
    -   XML
    -   Ctype
    -   JSON
    -   BCMath
    -   Fileinfo

### 2. **Document Root**

Create `.htaccess` in `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 3. **File Permissions**

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

## 🚀 Deployment Commands

After uploading files, run these commands:

```bash
# 1. Install dependencies
composer install --optimize-autoloader --no-dev

# 2. Generate application key
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Seed database
php artisan db:seed

# 5. Create storage link
php artisan storage:link

# 6. Set up queues
php artisan queue:table
php artisan migrate

# 7. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing After Upload

### 1. **Test Website**

-   [ ] Homepage loads: `https://gnraw.com`
-   [ ] Products page: `https://gnraw.com/products`
-   [ ] Contact page: `https://gnraw.com/contact`

### 2. **Test Email**

-   [ ] Basic email: `https://gnraw.com/test-email`
-   [ ] Email config: `https://gnraw.com/test-email-config`
-   [ ] Check inbox: `info@gnraw.com`

### 3. **Test Admin**

-   [ ] Admin login: `https://gnraw.com/admin/login`
-   [ ] Username: `admin@gnraw.com`
-   [ ] Password: `123456`

## 🔒 Security & SSL

### 1. **Enable SSL**

1. Go to **SSL** in hPanel
2. Enable **Let's Encrypt SSL**
3. Force HTTPS redirect

### 2. **Update APP_URL**

```env
APP_URL=https://gnraw.com
```

### 3. **Remove Test Routes**

Remove these from `routes/web.php`:

-   `/test-email`
-   `/test-email-basic`
-   `/test-email-inquiry`
-   `/test-email-config`
-   `/test-email-all`
-   `/test-inquiry`

## 📊 Final Checklist

Before going live:

-   [ ] **All files uploaded**
-   [ ] **Database created and migrated**
-   [ ] **Email account created**
-   [ ] **Email configuration tested**
-   [ ] **SSL certificate enabled**
-   [ ] **File permissions set**
-   [ ] **Application key generated**
-   [ ] **Configuration cached**
-   [ ] **Test routes removed**
-   [ ] **Website loads correctly**
-   [ ] **Email sending works**
-   [ ] **Admin panel accessible**

## 🎯 Quick Start Commands

```bash
# 1. Upload all files to Hostinger
# 2. Create database and email account
# 3. Update .env file
# 4. Run deployment script:
chmod +x deploy_hostinger.sh
./deploy_hostinger.sh

# 5. Test: https://gnraw.com/test-email
# 6. Remove test routes
# 7. Go live! 🚀
```

Your Genuine Nutra application will be live on Hostinger with email working for `info@gnraw.com`! 🎉
