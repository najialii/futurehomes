#!/bin/bash

# Filament Diagnostics Script
echo "🔍 Filament Production Diagnostics"
echo "=================================="

# Check current directory
echo "📁 Current directory: $(pwd)"

# Check .env file
echo ""
echo "🔧 Environment Configuration:"
if [ -f .env ]; then
    echo "✅ .env file exists"
    echo "APP_ENV: $(grep APP_ENV .env)"
    echo "APP_URL: $(grep APP_URL .env)"
    echo "APP_DEBUG: $(grep APP_DEBUG .env)"
else
    echo "❌ .env file missing"
fi

# Check file permissions
echo ""
echo "🔐 File Permissions:"
ls -la storage/
ls -la bootstrap/cache/

# Check if storage link exists
echo ""
echo "🔗 Storage Link:"
if [ -L public/storage ]; then
    echo "✅ Storage link exists: $(readlink public/storage)"
else
    echo "❌ Storage link missing"
fi

# Check Laravel status
echo ""
echo "🚀 Laravel Status:"
php artisan --version
php artisan route:list | grep admin

# Check database connection
echo ""
echo "🗄️ Database Connection:"
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connected successfully'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

# Check admin users
echo ""
echo "👤 Admin Users:"
php artisan tinker --execute="echo 'Admin users: ' . App\Models\User::whereHas('roles', function(\$q) { \$q->where('name', 'admin'); })->count();"

# Check recent logs
echo ""
echo "📋 Recent Logs (last 10 lines):"
if [ -f storage/logs/laravel.log ]; then
    tail -10 storage/logs/laravel.log
else
    echo "No log file found"
fi

# Check web server status
echo ""
echo "🌐 Web Server:"
curl -I http://localhost/admin 2>/dev/null | head -1 || echo "Could not reach admin panel"

echo ""
echo "🏁 Diagnostics completed!"