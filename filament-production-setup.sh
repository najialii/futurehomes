#!/bin/bash

# Filament Production Setup Script
# Run this on your server at 5.189.163.66

echo "🚀 Starting Filament Production Setup..."

# Navigate to project directory
cd /var/www/html || { echo "❌ Project directory not found"; exit 1; }

echo "📁 Current directory: $(pwd)"

# 1. Environment Check
echo "🔧 Setting up production environment..."
if [ -f .env.production ]; then
    cp .env.production .env
    echo "✅ Copied .env.production to .env"
else
    echo "❌ .env.production file not found"
    exit 1
fi

# Update APP_URL in .env
sed -i 's|APP_URL=.*|APP_URL=http://5.189.163.66|g' .env
echo "✅ Updated APP_URL to http://5.189.163.66"

# 2. Install/Update Dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

# 3. Laravel Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Storage Link
echo "🔗 Creating storage link..."
php artisan storage:link

# 5. Database Migration and Seeding
echo "🗄️ Running database migrations..."
php artisan migrate --force
php artisan db:seed --class=RolesAndPermissionsSeeder --force

# 6. Filament Optimization
echo "🎨 Optimizing Filament..."
php artisan filament:optimize

# 7. Set Permissions
echo "🔐 Setting file permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 8. Check for admin user
echo "👤 Checking for admin users..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'admin'); })->count();")

if [ "$USER_COUNT" -eq 0 ]; then
    echo "⚠️  No admin user found. Creating admin user..."
    php artisan make:filament-user
else
    echo "✅ Admin user exists"
fi

# 9. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 10. Final optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Filament production setup completed!"
echo "🌐 Admin panel should be accessible at: http://5.189.163.66/admin"
echo ""
echo "📋 Next steps:"
echo "1. Test the admin login at http://5.189.163.66/admin"
echo "2. Check logs if there are any issues: tail -f storage/logs/laravel.log"
echo "3. Verify nginx configuration is serving the public directory"