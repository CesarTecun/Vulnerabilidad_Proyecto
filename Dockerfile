FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones PHP necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    libssl-dev libpq-dev libicu-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar el contenido del proyecto al contenedor
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar clave de aplicación (si .env ya existe)
RUN if [ -f .env ]; then php artisan key:generate; fi

# Exponer puerto 8000
EXPOSE 8000

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
