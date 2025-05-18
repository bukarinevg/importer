FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    openssl && \
    docker-php-ext-install pdo_mysql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 775 storage/logs \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs

CMD ["php-fpm"]
