#!/bin/bash

# Create proper Nginx configuration for Laravel + React
cat > /etc/nginx/sites-available/futurehomes << 'EOF'
server {
    listen 8080;
    server_name _;
    root /var/www/futurehomessa.com/futurehomes/dist;
    index index.html;

    # API routes - Laravel backend
    location /api {
        root /var/www/futurehomessa.com/backend/public;
        rewrite ^/api/(.*)$ /$1 break;
        try_files $uri $uri/ /index.php?$query_string;
        
        # Add CORS headers
        add_header 'Access-Control-Allow-Origin' '*' always;
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS' always;
        add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization, X-Requested-With' always;
        
        # Handle preflight requests
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'Content-Type, Authorization, X-Requested-With';
            add_header 'Access-Control-Max-Age' 1728000;
            add_header 'Content-Type' 'text/plain; charset=utf-8';
            add_header 'Content-Length' 0;
            return 204;
        }
        
        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
            fastcgi_param PATH_INFO $fastcgi_path_info;
        }
    }

    # Admin Panel routes - Laravel Filament
    location /admin {
        root /var/www/futurehomessa.com/backend/public;
        try_files $uri $uri/ /index.php?$query_string;
        
        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
            fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
            fastcgi_param PATH_INFO $fastcgi_path_info;
        }
    }

    # Handle PHP files for Laravel (fallback)
    location ~ \.php$ {
        root /var/www/futurehomessa.com/backend/public;
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME /var/www/futurehomessa.com/backend/public/index.php;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # Frontend - React SPA (default)
    location / {
        try_files $uri $uri/ /index.html;
        add_header Cache-Control "no-cache, no-store, must-revalidate";
        add_header Pragma "no-cache";
        add_header Expires "0";
    }

    # Static assets
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
nginx -t && systemctl reload nginx

echo "Nginx configuration updated. Testing..."
echo "API test:"
curl -s http://localhost:8080/api/stats | head -5
echo ""
echo "Admin test:"
curl -s -I http://localhost:8080/admin | grep -E "(HTTP|Location|Content-Type)"