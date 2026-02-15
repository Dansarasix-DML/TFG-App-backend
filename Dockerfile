FROM php:8.3-apache

# Carpeta de trabajo
WORKDIR /var/www/html

# Instalar extensiones y dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_pgsql mbstring zip exif pcntl \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar Laravel
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY backend/ .

# Ajustar permisos
RUN chown -R www-data:www-data storage bootstrap/cache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Apuntar DocumentRoot a public
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]
