FROM php:8.3-fpm-alpine

# System deps
RUN apk add --no-cache \
    bash git unzip zip \
    icu-dev oniguruma-dev libzip-dev \
    postgresql-dev \
    linux-headers \
    curl \
    $PHPIZE_DEPS

# PHP extensions
RUN docker-php-ext-install \
    intl mbstring zip \
    pdo pdo_pgsql

# Composer (копируем из официального образа)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Xdebug (dev only, но ставим в образ; включим настройками)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/html/src
