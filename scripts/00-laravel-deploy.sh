#!/usr/bin/env bash
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan migrate --force