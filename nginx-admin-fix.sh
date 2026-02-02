#!/bin/bash

echo "Fixing Nginx configuration for admin panel access..."

# Create proper Nginx configuration for Laravel admin panel
cat > /etc/nginx/sites-available/futurehomes << 'EOF'
server {
    listen 8080;
    server_name _;
    root /var/www/futurehomessa.com/futurehomes/dist;
    index index.html;

    # Admin Panel - Laravel Filament (highest priority)
    location /admin {
        root /var/www/futurehomessa.com/backend/public;
        try_files $uri $uri/ /index.php?$query_string;
        
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
            include fastcgi_params;
            fastcgi_param REQUEST_URI $request_uri;
        }
    }

    # API routes - Laravel backend
    location /api {
        root /var/www/futurehomessa.com/backend/public;
        try_files $uri $uri/ /index.php?$query_string;
        
        # Add CORS headers
        add_header 'Access-Control-Allow-Origin' '*' always;
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
        add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization, X-Requested-With' always;
        
        # Handle preflight requests
        if ($request_method = 'OPTIONS') {
            return 204;
        }
        
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
            include fastcgi_params;
            fastcgi_param REQUEST_URI $request_uri;
        }
    }

    # Laravel static assets (CSS, JS for admin panel)
    location /css/ {
        root /var/www/futurehomessa.com/backend/public;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    location /js/ {
        root /var/www/futurehomessa.com/backend/public;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Livewire assets
    location /livewire/ {
        root /var/www/futurehomessa.com/backend/public;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Frontend - React SPA (default/fallback)
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Frontend static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Logs
    access_log /var/log/nginx/futurehomes_access.log;
    error_log /var/log/nginx/futurehomes_error.log;
}
EOF

# Test and reload Nginx
echo "Testing Nginx configuration..."
nginx -t

if [ $? -eq 0 ]; then
    echo "Configuration is valid. Reloading Nginx..."
    systemctl reload nginx
    echo "✅ Nginx reloaded successfully!"
    
    echo "Testing admin panel access..."
    curl -I http://localhost:8080/admin 2>/dev/null | head -1
    
    echo "Testing API access..."
    curl -s http://localhost:8080/api/stats | head -3
else
    echo "❌ Nginx configuration has errors. Please check the syntax."
fi