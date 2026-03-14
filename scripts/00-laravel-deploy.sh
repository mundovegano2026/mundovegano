#!/usr/bin/env bash

echo "Clearing cache..."
rm -rf /var/www/html/bootstrap/cache/*.php

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