#!/bin/bash

echo "🚀 Deploying frontend to production server..."

# Copy built files to server
rsync -avz --delete futurehomes/dist/ root@5.189.163.66:/var/www/futurehomessa.com/futurehomes/dist/

echo "✅ Frontend deployed successfully!"
echo "🌐 Visit: https://futurehomessa.com"