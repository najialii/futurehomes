#!/bin/bash

# Fix nginx SSL configuration for futurehomessa.com

cat > /etc/nginx/sites-available/futurehomessa << 'EOF'
# HTTPS server block
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    
    server_name futurehomessa.com www.futurehomessa.com;
    root /var/www/futurehomessa.com/futurehomes/dist;
    index index.html;

    # SSL certificates managed by Certbot
    ssl_certificate /etc/letsencrypt/live/futurehomessa.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/futurehomessa.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    # API proxy
    location /api {
        proxy_pass http://127.0.0.1:8081;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # Images proxy
    location /images {
        proxy_pass http://127.0.0.1:8081/storage/images;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    # Storage proxy
    location /storage {
        proxy_pass http://127.0.0.1:8081;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }

    # Frontend SPA routing
    location / {
        try_files $uri $uri/ /index.html;
    }
}

# HTTP to HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    
    server_name futurehomessa.com www.futurehomessa.com;
    
    return 301 h