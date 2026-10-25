FROM php:8.2-fpm

# Install dependensi sistem & paket pendukung PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

# Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Hak akses folder storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8080

# Jalankan config clear agar membaca environment variables dari Render
RUN php artisan config:clear

# Jalankan server web langsung
CMD php artisan serve --host=0.0.0.0 --port=$PORT