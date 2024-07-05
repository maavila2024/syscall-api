FROM php:8.3-cli AS builder

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY ./ /var/www

RUN composer install --optimize-autoloader

FROM php:8.3-fpm-alpine

RUN addgroup -g 1000 www \
    && adduser -G www -u 1000 -D www

WORKDIR /var/www

RUN apk add --no-cache postgresql-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql

COPY --from=builder /var/www /var/www

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
COPY ./docker/entrypoint.sh /entrypoint.sh

RUN if [ ! -d /var/www/storage ]; then mkdir -p /var/www/storage; fi \
    && chown -R www-data:www-data /var/www \
    && chown -R www:www /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod +x /entrypoint.sh

EXPOSE 9000

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
