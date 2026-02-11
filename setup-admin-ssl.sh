#!/bin/bash

echo "Setting up SSL for Filament admin panel..."

# Upload the new nginx config
scp nginx-admin-ssl.conf root@5.189.163.66:/etc/nginx/sites-available/admin.futurehomessa.com

# SSH to server and configure
ssh root@5.189.163.66 << 'EOF'
# Enable the new site
ln -sf /etc/nginx/sites-available/admin.futurehomessa.com /etc/nginx/sites-enabled/

# Test nginx configuration
nginx -t

if [ $? -eq 0 ]; then
    echo "Nginx config is valid, reloading..."
    systemctl reload nginx
    
    # Install certbot if not already installed
    apt update
    apt install -y certbot python3-certbot-nginx
    
    # Get SSL certificate for admin subdomain
    certbot --nginx -d admin.futurehomessa.com --non-interactive --agree-tos --email admin@futurehomessa.com
    
    echo "SSL certificate installed successfully!"
    echo "Admin panel is now available at: https://admin.futurehomessa.com"
else
    echo "Nginx configuration error! Please check the config."
fi
EOF