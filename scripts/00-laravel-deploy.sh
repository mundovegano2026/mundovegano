#!/usr/bin/env bash

echo "Installing PHP dependencies..."
composer install --no-dev --working-dir=/var/www/html

echo "Installing Node dependencies & building frontend..."
npm install && npm run prod

echo "Fixing storage permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chown -R nginx:nginx /var/www/html/storage
chown -R nginx:nginx /var/www/html/bootstrap/cache

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force