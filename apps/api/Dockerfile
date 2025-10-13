FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache icu-dev libzip-dev libpng-dev oniguruma-dev bash $PHPIZE_DEPS \
 && docker-php-ext-install pdo pdo_mysql intl opcache \
 && pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-progress --no-interaction --no-scripts
COPY . .
RUN composer dump-autoload --optimize


FROM base AS app
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html

RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]
