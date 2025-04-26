FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    libpq-dev \
    libzip-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libbrotli-dev \
    unzip \
    inotify-tools \
    && docker-php-ext-install pdo pdo_mysql zip pcntl

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install swoole-6.0.2 && docker-php-ext-enable swoole

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN if [ -f package.json ]; then npm install; fi
RUN pecl install redis && docker-php-ext-enable redis

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=8000", "--watch"]