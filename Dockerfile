## Multi-stage Dockerfile for Laravel (PHP-FPM + Nginx) on Dokploy
## - Builds PHP dependencies with Composer
## - Builds front-end assets with Vite (Filament 4 compatible)
## - Runs Nginx + PHP-FPM via supervisord

# 1) Composer deps
FROM php:8.4-cli-alpine AS vendor
WORKDIR /app

# Install tools/extensions required to satisfy Composer platform checks
RUN set -eux; \
    apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    ; \
    docker-php-ext-install -j"$(nproc)" \
    calendar \
    exif \
    intl \
    pcntl \
    zip

# Install Composer (copy binary from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Leverage caching by only copying composer files first
COPY composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

# 2) Node build for Vite/Filament assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json ./
# If you maintain a lockfile, uncomment to take advantage of build cache
# COPY package-lock.json ./
# Install patch-package first to ensure it's available when dependencies'
# postinstall scripts run (some packages like rollup require it)
RUN npm install patch-package --no-audit --no-fund --save-dev
# Now install all dependencies (patch-package is already installed, so npm won't reinstall it)
RUN npm install --no-audit --no-fund

# Copy the full project (pruned by .dockerignore) so Tailwind/Vite/Filament configs are included
COPY . .

# Ensure PHP vendor assets (used by Filament theme imports) are available during the build
COPY --from=vendor /app/vendor ./vendor

# Build assets
RUN npm run build

# 3) Final runtime image with PHP-FPM + Nginx
FROM php:8.4-fpm-alpine AS runtime

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PHP_MEMORY_LIMIT=256M \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

WORKDIR /var/www/html

# System deps
RUN set -eux; \
    apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    nodejs \
    npm \
    openssl \
    pcre2 \
    zlib \
    libstdc++ \
    icu-dev \
    libzip-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    freetype-dev \
    libwebp-dev \
    oniguruma-dev \
    shadow \
    git \
    ; \
    # PHP extensions commonly needed for Laravel + Media handling
    docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    ; \
    docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    bcmath \
    exif \
    intl \
    pcntl \
    zip \
    gd \
    opcache \
    ;



# Add Redis extension to match phpredis client usage
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install redis; \
    docker-php-ext-enable redis; \
    apk del .build-deps

# Add Imagick extension for image processing with AVIF support
RUN set -eux; \
    apk add --no-cache --virtual .build-deps-imagick $PHPIZE_DEPS imagemagick-dev libavif-dev; \
    apk add --no-cache imagemagick libavif; \
    pecl install imagick; \
    docker-php-ext-enable imagick; \
    apk del .build-deps-imagick

# Copy app source
COPY . .

# Copy vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Ensure storage and bootstrap cache are writable
RUN set -eux; \
    mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/logs bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    find storage -type d -exec chmod 775 {} \; ; \
    find storage -type f -exec chmod 664 {} \; ; \
    chmod -R 775 bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/site.conf /etc/nginx/http.d/default.conf

# PHP config for large file uploads
COPY docker/php.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Supervisor config
COPY docker/supervisord.conf /etc/supervisord.conf

# Entrypoint script to warm caches and start services
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=30s \
    CMD wget -qO- http://127.0.0.1/healthz || exit 1

ENTRYPOINT ["/entrypoint.sh"]
