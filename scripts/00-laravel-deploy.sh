#!/usr/bin/env bash

echo "Clearing cache..."
rm -rf /var/www/html/bootstrap/cache/*.php

echo "Fixing permissions..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chmod 777 /var/www/html/storage/logs/laravel.log

echo "Running package discover..."
php artisan package:discover --ansi

echo "Building frontend..."
npm install && npm run prod

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force