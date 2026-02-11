#!/bin/bash

# Fix nginx HTTPS configuration for futurehomessa.com

cat > /etc/nginx/sites-available/futurehomessa << 'EOF'
server {
    listen 80;
    server_name futurehomessa.com www.futurehomessa.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name futurehomessa.com www.futurehomessa.com;
    
    ssl_certificate /etc/letsencrypt/live/futurehomessa.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/futurehomessa.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    root /var/www/futurehomessa.com/futurehomes/dist;
    index index.html;

    location /api {
        proxy_pass http://127.0.0.1:8081;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    location /images {
        proxy_pass http://127.0.0.1:8081/storage/images;
        proxy_set_header Host $host;
    }

    location /storage {
        proxy_pass http://127.0.0.1:8081;
        proxy_set_header Host $host;
    }

    location / {
        try_files $uri $uri/ /index.html;
    }
}
EOF

echo "Testing nginx configuration..."
nginx -t

if [ $? -eq 0 ]; then
    echo "Config