# Production Email Setup Guide

This guide will help you configure email functionality for production deployment.

## 📧 Email Configuration Options

### 1. **SMTP Configuration (Recommended)**

#### For Hostinger/Shared Hosting:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Genuine Nutra"
```

#### For VPS/Dedicated Server:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Genuine Nutra"
```

### 2. **Gmail SMTP Setup**

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password**:
    - Go to Google Account Settings
    - Security → 2-Step Verification → App passwords
    - Generate password for "Mail"
3. **Use App Password** in your `.env` file

### 3. **Professional Email Services**

#### SendGrid:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
```

#### Mailgun:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_smtp_username
MAIL_PASSWORD=your_mailgun_smtp_password
MAIL_ENCRYPTION=tls
```

## 🔧 Production Environment Setup

### 1. **Update .env File**

Create a production `.env` file with these settings:

```env
# Application
APP_NAME="Genuine Nutra"
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_secure_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Genuine Nutra"

# Queue Configuration (for email processing)
QUEUE_CONNECTION=database
```

### 2. **Update Mail Configuration**

Update `config/mail.php` for production:

```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'noreply@yourdomain.com'),
    'name' => env('MAIL_FROM_NAME', 'Genuine Nutra'),
],
```

### 3. **Queue Configuration**

For better email performance, use queues:

```bash
# Install queue tables
php artisan queue:table
php artisan migrate

# Start queue worker
php artisan queue:work
```

## 🧪 Email Testing

### 1. **Test Email Route**

Add this route to test email functionality:

```php
Route::get('/test-email', function() {
    try {
        Mail::raw('Test email from Genuine Nutra', function ($message) {
            $message->to('test@example.com')
                   ->subject('Test Email');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});
```

### 2. **Email Testing Commands**

```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# Check queue status
php artisan queue:work --once
```

## 📋 Production Deployment Checklist

### Before Going Live:

-   [ ] **Email Configuration**: Test all email types
-   [ ] **SMTP Settings**: Verify with hosting provider
-   [ ] **Queue Workers**: Set up background email processing
-   [ ] **Email Templates**: Test all notification templates
-   [ ] **Error Handling**: Ensure graceful email failures
-   [ ] **Logging**: Monitor email sending logs

### Email Types to Test:

-   [ ] **Supplier Registration**: Welcome emails
-   [ ] **Product Inquiries**: Customer to supplier
-   [ ] **Admin Notifications**: System alerts
-   [ ] **Password Reset**: User authentication
-   [ ] **Contact Form**: General inquiries

## 🚨 Common Issues & Solutions

### Issue 1: "Connection could not be established"

**Solution:**

-   Check SMTP credentials
-   Verify port and encryption settings
-   Test with hosting provider's SMTP

### Issue 2: "Authentication failed"

**Solution:**

-   Use app passwords for Gmail
-   Check username/password combination
-   Verify 2FA is enabled

### Issue 3: "Emails not sending"

**Solution:**

-   Check queue workers are running
-   Verify email addresses are valid
-   Check spam folders

### Issue 4: "Slow email sending"

**Solution:**

-   Use queue workers for background processing
-   Consider professional email service
-   Optimize email templates

## 🔒 Security Best Practices

1. **Use App Passwords**: Never use main account passwords
2. **Environment Variables**: Keep credentials in `.env` file
3. **HTTPS Only**: Use secure connections
4. **Rate Limiting**: Implement email rate limits
5. **Validation**: Validate all email addresses

## 📊 Monitoring & Maintenance

### Log Monitoring:

```bash
# Check email logs
tail -f storage/logs/laravel.log | grep -i mail

# Monitor queue jobs
php artisan queue:monitor
```

### Performance Optimization:

-   Use Redis for queue processing
-   Implement email caching
-   Monitor email delivery rates
-   Set up email bounce handling

## 🎯 Recommended Production Setup

For a production environment, I recommend:

1. **Use Professional Email Service** (SendGrid, Mailgun, or AWS SES)
2. **Implement Queue Workers** for background processing
3. **Set up Email Monitoring** and bounce handling
4. **Use Dedicated Email Domain** (noreply@yourdomain.com)
5. **Implement Rate Limiting** to prevent abuse

This setup ensures reliable email delivery and professional appearance for your production application.
