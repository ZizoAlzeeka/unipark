# ============================================
# UniPark - Smart Campus Parking System
# Lightweight Dockerfile for Coolify (low-memory VPS friendly)
# Build: 2026-06-19-v2
# ============================================

FROM php:8.2-apache

# --------------------------------------------
# 1) System dependencies (minimal)
# --------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --------------------------------------------
# 2) PHP extensions (only what the project needs)
#    - intl is REMOVED (heavy, not required by composer.json)
#    - mysqli REMOVED (Laravel uses pdo_mysql only)
# --------------------------------------------
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# --------------------------------------------
# 3) PHP production config
# --------------------------------------------
RUN echo "upload_max_filesize = 30M" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 35M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 180" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.enable = 1" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.memory_consumption = 128" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.interned_strings_buffer = 8" >> /usr/local/etc/php/conf.d/custom.ini

# --------------------------------------------
# 4) Apache configuration
# --------------------------------------------
RUN a2enmod rewrite headers deflate expires

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' >> /etc/apache2/apache2.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# --------------------------------------------
# 5) Composer (installed once, used for autoload)
# --------------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# --------------------------------------------
# 6) Copy application FIRST (so we can install deps)
# --------------------------------------------
COPY . .

# Clean any stale Laravel bootstrap cache that may have leaked into the
# build context (services.php / packages.php from a dev machine). These
# files reference dev-only packages (Collision, Ignition, Sail) which
# are NOT installed with --no-dev and would crash artisan at runtime.
RUN rm -f bootstrap/cache/services.php bootstrap/cache/packages.php

# Install PHP dependencies WITHOUT running artisan scripts.
# Reason: artisan commands need a DB connection (CACHE_DRIVER=database),
# which is NOT available at build time. The entrypoint will run
# config:cache / route:cache / view:cache at container start instead.
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts \
    && composer dump-autoload --no-dev --optimize --no-scripts

# --------------------------------------------
# 7) Prepare storage / bootstrap cache dirs
# --------------------------------------------
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/cache/data \
    && mkdir -p storage/app/public \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# --------------------------------------------
# 8) Permissions
# --------------------------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --------------------------------------------
# 9) Entrypoint
# --------------------------------------------
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --------------------------------------------
# 10) Port + Healthcheck
# --------------------------------------------
# IMPORTANT: HEALTHCHECK is intentionally disabled (NONE).
# Coolify has its own healthcheck system on the UI side, and the
# previous in-image healthcheck (curl http://localhost/) returned 500
# during the Laravel boot phase, which made Coolify roll back every
# deployment. Let Coolify handle healthcheck via its UI instead.
ENV PORT=80
EXPOSE ${PORT}

HEALTHCHECK NONE

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
