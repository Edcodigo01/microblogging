# Usamos la imagen oficial de PHP-FPM con PHP 8.2
FROM php:8.2-fpm

# Instalar dependencias del sistema y Supervisor
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpq-dev \
    supervisor

# Instalar la extensión de PostgreSQL para PHP (pdo y pdo_pgsql)
RUN docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de Laravel al contenedor
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos para storage y bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Asegurar que PHP-FPM escuche en 0.0.0.0:9000
RUN sed -i 's/^listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

# Copiar el archivo de configuración de Supervisor
COPY supervisord.conf /etc/supervisord.conf

# Exponer el puerto en el que PHP-FPM atenderá (Nginx se conectará a este)
EXPOSE 9000

# Iniciar Supervisor (el cual iniciará PHP-FPM y el worker de Laravel)
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
