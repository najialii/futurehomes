#!/bin/bash

echo "=== Testing Laravel Backend Directly ==="

# Test if Laravel is accessible via PHP-FPM
echo "1. Testing Laravel index.php directly:"
cd /var/www/futurehomessa.com/backend/public
php -S localhost:9000 index.php &
PHP_PID=$!
sleep 2

echo "Testing direct PHP server on port 9000:"
curl -s http://localhost:9000/api/stats | head -5

# Kill the PHP server
kill $PHP_PID 2>/dev/null

echo ""
echo "2. Testing Laravel artisan routes:"
cd /var/www/futurehomessa.com/backend
php artisan route:list --path=api

echo ""
echo "3. Testing Laravel configuration:"
php artisan config:cache
php artisan route:cache

echo ""
echo "4. Checking Laravel logs:"
tail -10 /var/www/futurehomessa.com/backend/storage/logs/laravel.log

echo ""
echo "5. Testing database connection:"
php artisan tinker --execute="echo 'DB Connection: ' . (DB::connection()->getPdo() ? 'OK' : 'Failed');"