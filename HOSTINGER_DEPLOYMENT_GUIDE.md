# Hostinger Deployment Guide for Genuine Nutra

This guide will help you deploy your Laravel project to Hostinger with email configuration for `info@gnraw.com`.

## 🚀 Step 1: Prepare Your Project

### 1.1 Update Environment Configuration

```bash
# Copy the Hostinger configuration
cp env_hostinger_gnraw.txt .env

# Update with your actual database credentials
# Edit .env file with your Hostinger database details
```

### 1.2 Optimize for Production

```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📁 Step 2: Upload to Hostinger

### 2.1 File Upload Methods

#### Option A: File Manager (cPanel)

1. **Login to Hostinger hPanel**
2. **Go to File Manager**
3. **Navigate to `public_html`**
4. **Upload your project files** (except `vendor` folder)
5. **Extract the files** if uploaded as ZIP

#### Option B: FTP/SFTP

```bash
# Using FileZilla or similar FTP client
Host: your-domain.com
Username: your_hostinger_username
Password: your_hostinger_password
Port: 21 (FTP) or 22 (SFTP)
```

#### Option C: Git (Recommended)

```bash
# On Hostinger terminal (if available)
git clone https://github.com/your-username/Genuine-nutra.git
cd Genuine-nutra
composer install --optimize-autoloader --no-dev
```

### 2.2 File Structure on Hostinger

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
├── composer.json
└── public/
    ├── index.php
    ├── css/
    ├── js/
    └── images/
```

## 🗄️ Step 3: Database Setup

### 3.1 Create Database in Hostinger

1. **Login to hPanel**
2. **Go to MySQL Databases**
3. **Create New Database:**
    - Database Name: `gnraw_db` (or your preferred name)
    - Username: `gnraw_user` (or your preferred name)
    - Password: Generate strong password
4. **Note down the credentials**

### 3.2 Update .env File

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=gnraw_db
DB_USERNAME=gnraw_user
DB_PASSWORD=your_database_password
```

### 3.3 Run Migrations

```bash
# Via Hostinger terminal or SSH
php artisan migrate --force
php artisan db:seed
```

## 📧 Step 4: Email Configuration

### 4.1 Create Email Account in Hostinger

1. **Login to hPanel**
2. **Go to Email Accounts**
3. **Create New Email:**
    - Email: `info@gnraw.com`
    - Password: Generate strong password
    - Mailbox Quota: 10GB (or as needed)

### 4.2 Update .env with Email Settings

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

### 4.3 Alternative Hostinger SMTP Settings

If the above doesn't work, try:

```env
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

## ⚙️ Step 5: Server Configuration

### 5.1 Update Document Root

1. **Go to hPanel → Advanced → PHP Configuration**
2. **Set Document Root to:** `public_html/public`
3. **Or create .htaccess in public_html:**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 5.2 PHP Configuration

1. **Set PHP Version to 8.1 or higher**
2. **Enable Required Extensions:**
    - OpenSSL
    - PDO
    - Mbstring
    - Tokenizer
    - XML
    - Ctype
    - JSON
    - BCMath
    - Fileinfo

### 5.3 File Permissions

```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

## 🔧 Step 6: Laravel Configuration

### 6.1 Update .env File

```env
# Application
APP_NAME="Genuine Nutra"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gnraw.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=gnraw_db
DB_USERNAME=gnraw_user
DB_PASSWORD=your_database_password

# Email
MAIL_MAILER=smtp
MAIL_HOST=mail.gnraw.com
MAIL_PORT=587
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"

# Queue
QUEUE_CONNECTION=database
```

### 6.2 Run Laravel Commands

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set up queues
php artisan queue:table
php artisan migrate
```

## 🧪 Step 7: Testing

### 7.1 Test Email Configuration

```bash
# Test basic email
curl https://gnraw.com/test-email

# Test email configuration
curl https://gnraw.com/test-email-config

# Test all email types
curl https://gnraw.com/test-email-all
```

### 7.2 Test Application Features

-   [ ] **Homepage loads**: `https://gnraw.com`
-   [ ] **Products page**: `https://gnraw.com/products`
-   [ ] **Contact form**: `https://gnraw.com/contact`
-   [ ] **Admin login**: `https://gnraw.com/admin/login`
-   [ ] **Email sending**: Check `info@gnraw.com` inbox

## 🔒 Step 8: Security & SSL

### 8.1 Enable SSL Certificate

1. **Go to hPanel → SSL**
2. **Enable Let's Encrypt SSL**
3. **Force HTTPS redirect**

### 8.2 Update APP_URL

```env
APP_URL=https://gnraw.com
```

### 8.3 Remove Test Routes (Production)

```bash
# Remove these lines from routes/web.php:
# - /test-email
# - /test-email-basic
# - /test-email-inquiry
# - /test-email-config
# - /test-email-all
# - /test-inquiry
```

## 📊 Step 9: Monitoring & Maintenance

### 9.1 Set Up Queue Workers

```bash
# Create cron job in Hostinger
# Add this to crontab:
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1

# Or use Hostinger's cron job feature
```

### 9.2 Monitor Logs

```bash
# Check application logs
tail -f storage/logs/laravel.log

# Check email logs
grep -i mail storage/logs/laravel.log
```

## 🚨 Troubleshooting

### Common Issues:

#### 1. 500 Internal Server Error

-   Check file permissions
-   Verify .env configuration
-   Check Laravel logs

#### 2. Email Not Sending

-   Verify SMTP credentials
-   Check email account in Hostinger
-   Test with different SMTP settings

#### 3. Database Connection Error

-   Verify database credentials
-   Check if database exists
-   Test connection

#### 4. File Upload Issues

-   Check storage permissions
-   Verify storage link exists
-   Check file size limits

## 📋 Final Checklist

Before going live:

-   [ ] **Domain points to Hostinger**
-   [ ] **SSL certificate enabled**
-   [ ] **Database created and migrated**
-   [ ] **Email account created**
-   [ ] **Email configuration tested**
-   [ ] **File permissions set correctly**
-   [ ] **Application key generated**
-   [ ] **Configuration cached**
-   [ ] **Test routes removed**
-   [ ] **Queue workers configured**

## 🎯 Quick Commands Summary

```bash
# 1. Upload files to Hostinger
# 2. Create database and email account
# 3. Update .env file
# 4. Run these commands:

composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:table
php artisan migrate

# 5. Test email: https://gnraw.com/test-email
# 6. Remove test routes
# 7. Go live! 🚀
```

Your Genuine Nutra application should now be live on Hostinger with email functionality working for `info@gnraw.com`! 🎉
