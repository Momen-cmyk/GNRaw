#!/bin/bash

# Production Deployment Script for Genuine Nutra
# Run this script on your production server

echo "🚀 Starting Production Deployment for Genuine Nutra..."

# 1. Update code from repository
echo "📥 Pulling latest code..."
git pull origin main

# 2. Install/Update dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev

# 3. Generate application key (if not exists)
echo "🔑 Generating application key..."
php artisan key:generate

# 4. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Seed initial data (admin accounts)
echo "🌱 Seeding initial data..."
php artisan db:seed

# 6. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# 7. Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Set up queue tables (for email processing)
echo "📧 Setting up email queues..."
php artisan queue:table
php artisan migrate

# 9. Set proper permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 10. Test email configuration
echo "📧 Testing email configuration..."
php artisan tinker --execute="
try {
    \Illuminate\Support\Facades\Mail::raw('Production deployment test - ' . now(), function (\$message) {
        \$message->to('admin@yourdomain.com')->subject('Deployment Test');
    });
    echo 'Email test: SUCCESS';
} catch (Exception \$e) {
    echo 'Email test: FAILED - ' . \$e->getMessage();
}
"

echo "✅ Production deployment completed!"
echo ""
echo "📋 Next steps:"
echo "1. Update your .env file with production settings"
echo "2. Configure your email settings (SMTP/SendGrid/Mailgun)"
echo "3. Test email functionality at /test-email"
echo "4. Start queue workers: php artisan queue:work"
echo "5. Set up SSL certificate"
echo "6. Configure web server (Apache/Nginx)"
echo ""
echo "🔧 Email Configuration:"
echo "   - Copy env_production_template.txt to .env"
echo "   - Update MAIL_* settings with your email provider"
echo "   - Test with /test-email route"
echo ""
echo "📊 Monitoring:"
echo "   - Check logs: tail -f storage/logs/laravel.log"
echo "   - Monitor queues: php artisan queue:monitor"
echo "   - Test inquiry system: /test-inquiry"
