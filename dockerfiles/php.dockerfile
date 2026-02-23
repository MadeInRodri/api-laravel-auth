FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# 1. Dependencias del sistema 
RUN apk add --no-cache \
    mysql-client msmtp perl wget procps shadow \
    libzip libpng libjpeg-turbo libwebp freetype \
    icu git curl

# 2. Dependencias de compilación
RUN apk add --no-cache --virtual build-deps \
    icu-dev zlib-dev g++ make automake autoconf \
    libzip-dev libpng-dev libwebp-dev libjpeg-turbo-dev \
    freetype-dev pcre-dev $PHPIZE_DEPS

# 3. Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd intl bcmath opcache exif zip pdo pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis
RUN apk del build-deps

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar archivos de composer
COPY api-laravel-auth/composer.json api-laravel-auth/composer.lock ./

ENV COMPOSER_PROCESS_TIMEOUT=2000
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 6. Copiar resto del proyecto
COPY api-laravel-auth/ .
# Crear el archivo SQLite si no existe y dar permisos a la carpeta database
RUN mkdir -p database && touch database/database.sqlite

# 8. Corregir permisos (Añadimos la carpeta database)
# Sin esto, SQLite siempre será "Read-only"
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN php artisan view:cache

EXPOSE 9000

CMD ["php-fpm"]