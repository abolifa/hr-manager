FROM php:8.3-fpm-alpine

# System deps
RUN apk add --no-cache \
    bash git curl libpng libpng-dev libzip-dev oniguruma-dev icu-dev \
    libjpeg-turbo-dev freetype-dev zlib-dev autoconf make g++ shadow

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip intl gd bcmath opcache

# Opcache for dev (still helpful)
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=1"; \
  echo "opcache.validate_timestamps=1"; \
  echo "opcache.revalidate_freq=0"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create user matching host UID/GID for correct file perms on macOS
ARG UID=1000
ARG GID=1000
RUN groupmod -g ${GID} www-data && usermod -u ${UID} -g ${GID} www-data
USER www-data

WORKDIR /var/www/html

# Default command
CMD ["php-fpm", "-F"]
