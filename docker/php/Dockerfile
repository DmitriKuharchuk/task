# syntax=docker/dockerfile:1.6
FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y     git zip unzip libpq-dev libzip-dev libonig-dev libicu-dev     && docker-php-ext-install pdo pdo_pgsql intl     && docker-php-ext-enable pdo_pgsql intl     && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/services
COPY docker/php/entrypoint.sh /usr/local/bin/laravel-entrypoint
RUN chmod +x /usr/local/bin/laravel-entrypoint

EXPOSE 9000
ENTRYPOINT ["laravel-entrypoint"]
