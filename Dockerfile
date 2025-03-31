# Etapa 1: Construcción (para instalar dependencias sin afectar la imagen final)
FROM php:8.2-fpm AS build

# Instalar dependencias y extensiones
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd \
    && rm -rf /var/lib/apt/lists/*

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar solo los archivos necesarios para instalar dependencias
COPY composer.json composer.lock ./

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Etapa 2: Imagen final más liviana
FROM php:8.2-fpm

WORKDIR /var/www

# Copiar solo los archivos necesarios desde la imagen de construcción
COPY --from=build /var/www/vendor /var/www/vendor
COPY . .

# Establecer permisos para almacenamiento y caché
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]
