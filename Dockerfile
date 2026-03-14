FROM php:7.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app
COPY . /var/www/html/
WORKDIR /var/www/html

# Remove vendor and install fresh
RUN rm -rf vendor
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Permissions
RUN chmod -R 777 storage bootstrap/cache && \
    mkdir -p storage/logs && \
    touch storage/logs/laravel.log && \
    chmod 777 storage/logs/laravel.log

# Build frontend
RUN npm install && npm run prod

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

EXPOSE 80

CMD service nginx start && php-fpm