#!/bin/bash

# Hostinger Deployment Script for Genuine Nutra
# Run this script after uploading files to Hostinger

echo "🚀 Starting Hostinger Deployment for Genuine Nutra..."

# 1. Set proper permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# 2. Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev

# 3. Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# 4. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Seed initial data
echo "🌱 Seeding initial data..."
php artisan db:seed

# 6. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# 7. Set up queue tables
echo "📧 Setting up email queues..."
php artisan queue:table
php artisan migrate

# 8. Cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Test email configuration
echo "📧 Testing email configuration..."
php artisan tinker --execute="
try {
    \Illuminate\Support\Facades\Mail::raw('Hostinger deployment test - ' . now(), function (\$message) {
        \$message->to('info@gnraw.com')->subject('Deployment Test - Genuine Nutra');
    });
    echo 'Email test: SUCCESS - Check info@gnraw.com';
} catch (Exception \$e) {
    echo 'Email test: FAILED - ' . \$e->getMessage();
}
"

echo "✅ Hostinger deployment completed!"
echo ""
echo "📋 Next steps:"
echo "1. Test your website: https://gnraw.com"
echo "2. Test email: https://gnraw.com/test-email"
echo "3. Check admin login: https://gnraw.com/admin/login"
echo "4. Remove test routes from production"
echo "5. Set up SSL certificate in Hostinger hPanel"
echo ""
echo "📧 Email Configuration:"
echo "   - Email: info@gnraw.com"
echo "   - SMTP: mail.gnraw.com:587"
echo "   - Encryption: TLS"
echo ""
echo "🔧 Admin Accounts Created:"
echo "   - Super Admin: superadmin@gnraw.com / 123456"
echo "   - Admin: admin@gnraw.com / 123456"
echo ""
echo "🎉 Your application is now live on Hostinger!"
