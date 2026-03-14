FROM richarvey/nginx-php-fpm:2.0.1

COPY . /var/www/html/

WORKDIR /var/www/html

RUN rm -rf vendor

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

RUN chmod -R 777 /var/www/html/storage && \
    chmod -R 777 /var/www/html/bootstrap/cache && \
    mkdir -p /var/www/html/storage/logs && \
    touch /var/www/html/storage/logs/laravel.log && \
    chmod 777 /var/www/html/storage/logs/laravel.log

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]