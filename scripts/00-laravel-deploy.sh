#!/usr/bin/env bash

echo "Installing PHP dependencies..."
composer install --no-dev --working-dir=/var/www/html

echo "Installing Node dependencies & building frontend..."
npm install && npm run prod

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force