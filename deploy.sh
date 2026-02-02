#!/bin/bash

# Future Homes CMS Deployment Script
# This script deploys the Laravel Filament CMS to production server

echo "🚀 Starting Future Homes CMS Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="futurehomessa.com"
SERVER_IP="5.189.163.66"
SERVER_PORT="8090"
PROJECT_NAME="futurehomes-cms"

echo -e "${YELLOW}📋 Deployment Configuration:${NC}"
echo "Domain: $DOMAIN"
echo "Server: $SERVER_IP:$SERVER_PORT"
echo "Project: $PROJECT_NAME"
echo ""

# Step 1: Build Frontend
echo -e "${YELLOW}🔨 Building React Frontend...${NC}"
cd futurehomes
npm install
npm run build
cd ..

# Step 2: Prepare Backend
echo -e "${YELLOW}⚙️ Preparing Laravel Backend...${NC}"
cd backend

# Copy production environment file
cp .env.production .env

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key if needed
php artisan key:generate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

cd ..

echo -e "${GREEN}✅ Build completed successfully!${NC}"
echo ""
echo -e "${YELLOW}📝 Next Steps for Server Deployment:${NC}"
echo "1. Upload the entire project to your server"
echo "2. Point your domain to the 'futurehomes/dist' folder for frontend"
echo "3. Set up a subdomain or path for Laravel backend"
echo "4. Configure your web server (Apache/Nginx)"
echo "5. Set up the database and run migrations"
echo "6. Configure SSL certificate for HTTPS"
echo ""
echo -e "${GREEN}🎯 Target URLs:${NC}"
echo "Frontend: https://$DOMAIN/"
echo "Admin Panel: https://$DOMAIN/admin"
echo "API: https://$DOMAIN/api"
echo ""
echo -e "${YELLOW}⚠️ Important:${NC}"
echo "- Update .env file with your production database credentials"
echo "- Update MAIL settings in .env for contact form"
echo "- Ensure storage directory is writable"
echo "- Run 'php artisan migrate --seed' on the server"
echo ""
echo -e "${GREEN}🚀 Deployment preparation complete!${NC}"