#!/bin/bash
cd /var/www/futurehomessa.com/backend

# Update Livewire config to allow 50MB uploads
sed -i "s/'rules' => null,/'rules' => ['required', 'file', 'max:51200'],/" config/livewire.php

# Clear config cache
php artisan config:clear

echo "Livewire upload limit updated to 50MB"