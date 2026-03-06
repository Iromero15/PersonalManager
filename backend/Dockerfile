FROM php:8.3-fpm-alpine

# Instalamos dependencias del sistema requeridas por Laravel
RUN apk add --no-cache \
    linux-headers \
    build-base \
    zlib-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    unzip \
    curl

# Instalamos las extensiones de PHP (vital para conectarse a la DB y manejar strings)
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Traemos Composer (el gestor de paquetes de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Seteamos el directorio de trabajo
WORKDIR /var/www/backend

# Damos permisos básicos al usuario www-data
RUN chown -R www-data:www-data /var/www/backend