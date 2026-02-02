#!/bin/bash

echo "Setting up admin user and Filament configuration..."

cd /var/www/futurehomessa.com/backend

# Create admin user if it doesn't exist
echo "Creating admin user..."
php artisan tinker --execute="
if (!\App\Models\User::where('email', 'admin@futurehomes.com')->exists()) {
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@futurehomes.com',
        'password' => bcrypt('admin123'),
        'email_verified_at' => now()
    ]);
    echo 'Admin user created: admin@futurehomes.com / admin123';
} else {
    echo 'Admin user already exists';
}
"

# Publish Filament assets
echo "Publishing Filament assets..."
php artisan filament:assets

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
echo "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache

echo "✅ Admin setup completed!"
echo ""
echo "Admin Panel Access:"
echo "URL: http://5.189.163.66:8080/admin"
echo "Email: admin@futurehomes.com"
echo "Password: admin123"