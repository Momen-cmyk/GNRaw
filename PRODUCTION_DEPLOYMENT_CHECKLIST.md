# 🚀 Production Deployment Checklist

## ✅ **Environment File Updated for Production**

### **🔧 What I've Fixed in `env` file:**

#### **1. ✅ Application Settings:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://genuine-nutra.com
```

#### **2. ✅ Database Configuration:**

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=gnraw_db
DB_USERNAME=gnraw_user
DB_PASSWORD=your_database_password_here
```

#### **3. ✅ Email Configuration:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@gnraw.com
MAIL_PASSWORD=your_hostinger_email_password_here
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@gnraw.com
MAIL_FROM_NAME="Genuine Nutra"
```

#### **4. ✅ Production Optimizations:**

```env
LOG_LEVEL=error
QUEUE_CONNECTION=database
```

### **📝 Before Uploading - Update These Values:**

#### **1. Database Credentials:**

-   **Database Name**: `gnraw_db` (or your actual database name)
-   **Username**: `gnraw_user` (or your actual database username)
-   **Password**: Replace `your_database_password_here` with your actual database password

#### **2. Email Password:**

-   **Password**: Replace `your_hostinger_email_password_here` with your actual Hostinger email password

### **🚀 Upload Steps:**

#### **1. Prepare Files:**

```bash
# Copy env to .env for production
copy env .env

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### **2. Upload to Hostinger:**

-   Upload all project files to `public_html` directory
-   Upload `.env` file with your actual credentials
-   Ensure file permissions are correct (644 for files, 755 for directories)

#### **3. Database Setup:**

-   Create MySQL database in Hostinger hPanel
-   Import your database structure
-   Update database credentials in `.env`

#### **4. Post-Upload Commands:**

```bash
# Run these commands on Hostinger via SSH or terminal
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan migrate
php artisan db:seed
```

### **🧪 Test After Upload:**

#### **1. Test Email Configuration:**

```bash
curl https://genuine-nutra.com/test-cmail-config
```

#### **2. Test Password Reset:**

```bash
curl https://genuine-nutra.com/test-password-reset-email
```

#### **3. Test Contact Form:**

-   Go to `https://genuine-nutra.com/contact`
-   Fill out the form and submit
-   Check if email is received at `info@gnraw.com`

### **📋 Final Checklist:**

-   [ ] Database credentials updated in `.env`
-   [ ] Email password updated in `.env`
-   [ ] All files uploaded to Hostinger
-   [ ] Database created and imported
-   [ ] File permissions set correctly
-   [ ] Caches cleared
-   [ ] Email configuration tested
-   [ ] Contact form tested
-   [ ] Password reset tested

### **🔧 File Structure on Hostinger:**

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── composer.json
```

### **📊 Expected Results:**

-   ✅ Website loads at `https://genuine-nutra.com`
-   ✅ Email system works with Hostinger SMTP
-   ✅ Database connections work
-   ✅ All forms submit successfully
-   ✅ Password reset emails are sent
-   ✅ Contact inquiries are received

**Your production environment is ready for upload! 🎉**
