#!/bin/bash

# Create a simpler Nginx configuration using alias
cat > /etc/nginx/sites-available/futurehomes << 'EOF'
server {
    listen 8080;
    server_name _;

    # API routes - Laravel backend using alias
    location /api/ {
        alias /var/www/futurehomessa.com/backend/public/;
        try_files $uri $uri/ @api;
    
        add_header 'Access-Control-Allow-Origin' '*' always;
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
        add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization, X-Requested-With' always;
        
        # Handle preflight requests
        if ($request_method = 'OPTIONS') {
            return 204;
        }
    }

    # Admin Panel routes - Laravel Filament using alias
    location /admin/ {
        alias /var/www/futurehomessa.com/backend/public/;
        try_files $uri $uri/ @admin;
    }

    # Laravel API fallback
    location @api {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
        fastcgi_param REQUEST_URI /api$uri;
        include fastcgi_params;
    }

    # Laravel Admin fallback
    location @admin {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
        fastcgi_param REQUEST_URI $uri;
        include fastcgi_params;
    }

    # Frontend - React SPA (default)
    location / {
        root /var/www/futurehomessa.com/futurehomes/dist;
        try_files $uri $uri/ /index.html;
        index index.html;
    }

    # Static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        root /var/www/futurehomessa.com/futurehomes/dist;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Logs
    access_log /var/log/nginx/futurehomes_access.log;
    error_log /var/log/nginx/futurehomes_error.log;
}
EOF

# Test and reload Nginx
nginx -t && systemctl reload nginx

echo "Simple Nginx configuration applied. Testing..."
curl -s http://localhost:8080/api/stats | head -5