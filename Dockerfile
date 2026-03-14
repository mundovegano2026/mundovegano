FROM richarvey/nginx-php-fpm:2.0.1

COPY . /var/www/html/

WORKDIR /var/www/html

# Remove any existing vendor folder
RUN rm -rf vendor

# Install composer dependencies, skip post-install scripts
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Fix permissions
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R nginx:nginx storage bootstrap/cache

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]