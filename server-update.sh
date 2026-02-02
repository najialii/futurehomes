#!/bin/bash

# Server Update Script - Run this on your production server
echo "🔄 Updating Future Homes CMS on server..."

# Navigate to project directory
cd /var/www/futurehomessa.com

# Pull latest changes
echo "📥 Pulling latest changes from Git..."
git pull origin main

# Update backend
echo "⚙️ Updating Laravel backend..."
cd backend

# Install/update dependencies
composer install --optimize-autoloader --no-dev

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache optimized config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run any pending migrations
php artisan migrate --force

# Publish Filament assets (if needed)
php artisan filament:assets

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Backend updated successfully!"

# Update frontend
echo "🎨 Updating React frontend..."
cd ../futurehomes

# Install dependencies and build
npm install
npm run build

# Set proper permissions for dist folder
chown -R www-data:www-data dist

echo "✅ Frontend updated successfully!"

# Test the contact API
echo "🧪 Testing contact API..."
cd ../backend
curl -X POST http://localhost:8080/api/contact \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"Test User","email":"test@example.com","message":"Test deployment"}' \
  -s

echo ""
echo "🚀 Server update completed!"
echo "Contact API should now work without rate limiter errors."