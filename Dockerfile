# syntax=docker/dockerfile:1

# ==============================================================================
# Stage 1 — Build aset frontend (Vite + Tailwind)
# ==============================================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build


# ==============================================================================
# Stage 2 — Base PHP (dipakai bersama oleh stage vendor & app)
# ==============================================================================
FROM php:8.3-fpm-alpine AS php-base

# install-php-extensions menangani semua dependency build/runtime tiap ekstensi
ADD --chmod=0755 \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    /usr/local/bin/

RUN install-php-extensions \
        pdo_mysql \
        mbstring \
        bcmath \
        intl \
        exif \
        pcntl \
        zip \
        gd \
        opcache \
    && apk add --no-cache tzdata

WORKDIR /var/www/html


# ==============================================================================
# Stage 3 — Install dependency PHP (composer, tanpa dev)
# ==============================================================================
FROM php-base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

# Layer terpisah agar cache tidak hangus tiap kali kode berubah
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-progress

# Kode aplikasi, lalu autoloader yang teroptimasi
COPY . .
RUN composer dump-autoload --no-dev --no-scripts --optimize --classmap-authoritative


# ==============================================================================
# Stage 4 — Image aplikasi (PHP-FPM)
# ==============================================================================
FROM php-base AS app

COPY docker/php/php.ini   /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf  /usr/local/etc/php-fpm.d/zz-www.conf

COPY --from=vendor --chown=www-data:www-data /var/www/html      /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build  /var/www/html/public/build

COPY --chmod=0755 docker/entrypoint.sh /usr/local/bin/entrypoint

# Direktori runtime yang wajib ada & writable
RUN set -eux; \
    mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    chmod -R ug+rwX storage bootstrap/cache

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD pgrep -f "php-fpm: master" >/dev/null || exit 1

EXPOSE 9000

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm", "--nodaemonize"]


# ==============================================================================
# Stage 5 — Web server (Caddy, HTTP-only) — hanya butuh direktori public/
# ==============================================================================
FROM caddy:2-alpine AS web

COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY --from=app /var/www/html/public /var/www/html/public
