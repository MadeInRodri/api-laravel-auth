# 1. Usamos una imagen base oficial de PHP con FPM (FastCGI Process Manager)
# Esta versión es ligera y eficiente para servir APIs.
FROM php:8.2-fpm

# 2. Definimos el directorio de trabajo dentro del contenedor
WORKDIR /var/www

# 3. Instalamos dependencias del sistema operativo (Linux Debian en este caso)
# Necesitamos herramientas para descomprimir archivos y librerías para SQLite.
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libsqlite3-dev

# 4. Limpiamos el caché de paquetes para que la imagen sea más pequeña y eficiente
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 5. Instalamos las extensiones de PHP necesarias para Laravel y SQLite
RUN docker-php-ext-install pdo_mysql pdo_sqlite zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# 6. Descargamos la última versión de Composer desde su imagen oficial
# Esto nos permite ejecutar "composer install" dentro del contenedor.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Copiamos todos los archivos de tu proyecto local al contenedor
COPY . /var/www

# 8. Ajustamos los permisos de las carpetas de Laravel
# El servidor web necesita escribir en 'storage' y 'bootstrap/cache'.
# El usuario 'www-data' es el estándar para servidores web en Linux.
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Exponemos el puerto 9000 (el que usa PHP-FPM por defecto)
EXPOSE 9000

# 10. Comando para iniciar PHP-FPM
CMD ["php-fpm"]