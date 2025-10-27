# 🚀 Quick Deployment Checklist for Hostinger

## Before Uploading:

### 1. ✅ Files Ready

-   [ ] All project files are ready
-   [ ] `env_production_template.txt` created with your email (info@gnraw.com)
-   [ ] `HOSTINGER_DEPLOYMENT_GUIDE.md` ready for reference

### 2. ✅ Email Configuration

-   [ ] Email: `info@gnraw.com` configured
-   [ ] SMTP settings: `smtp.hostinger.com:587`
-   [ ] Encryption: `tls`

## On Hostinger Server:

### 3. ✅ Database Setup

-   [ ] Create MySQL database
-   [ ] Create database user
-   [ ] Note down database credentials

### 4. ✅ File Upload

-   [ ] Upload all files to `public_html/`
-   [ ] Rename `env_production_template.txt` to `.env`
-   [ ] Update `.env` with your database credentials
-   [ ] Update `APP_URL` with your domain

### 5. ✅ Composer & Laravel Setup

```bash
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. ✅ File Permissions

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## Testing After Deployment:

### 7. ✅ Website Functionality

-   [ ] Website loads correctly
-   [ ] Admin panel accessible
-   [ ] Supplier registration works
-   [ ] Product management works
-   [ ] File uploads work

### 8. ✅ Email Testing

-   [ ] Test supplier registration email
-   [ ] Test admin notifications
-   [ ] Test contact form
-   [ ] Check email delivery

### 9. ✅ Final Checks

-   [ ] SSL certificate active
-   [ ] All features working
-   [ ] No errors in logs

---

## 📧 Email Testing Commands (After Deployment)

Test these email features:

1. **Supplier Registration**: Register a new supplier
2. **Admin Notifications**: Add a product as supplier
3. **Contact Form**: Submit contact form
4. **Password Reset**: Test password reset functionality

---

## 🆘 If Something Goes Wrong:

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Check file permissions**: Ensure storage and cache folders are writable
3. **Verify .env settings**: Make sure all credentials are correct
4. **Test database connection**: Run `php artisan migrate:status`
5. **Clear caches**: Run `php artisan cache:clear && php artisan config:clear`

---

**Ready to deploy! 🚀**
