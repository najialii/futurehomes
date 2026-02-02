#!/bin/bash

echo "Testing contact API fix..."

# Navigate to backend directory
cd backend

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Test contact API
echo "Testing contact API..."
curl -X POST http://localhost:8080/api/contact \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"Test User","email":"test@example.com","message":"Test message"}' \
  -s | jq .

echo "Contact API test completed."