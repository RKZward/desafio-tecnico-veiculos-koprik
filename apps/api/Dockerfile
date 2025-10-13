
FROM php:8.2-fpm-alpine AS base


RUN apk add --no-cache icu-dev libzip-dev libpng-dev oniguruma-dev bash \
 && docker-php-ext-install pdo pdo_mysql intl opcache \
 && apk add --no-cache $PHPIZE_DEPS \
 && pecl install redis \
 && docker-php-ext-enable redis

WORKDIR /var/www/html


FROM composer:2 AS vendor
WORKDIR /app
COPY . /app
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
 && php artisan package:discover --ansi || true

FROM base AS app
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html

RUN php artisan key:generate --force || true \
 && php artisan storage:link || true \
 && php artisan config:cache || true \
 && php artisan route:cache || true

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]
