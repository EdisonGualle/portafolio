FROM php:8.3-fpm

# Paquetes necesarios para extensiones PHP
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libzip-dev zlib1g-dev \
    libicu-dev \
    libpq-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
    intl \
    gd \
    pdo pdo_mysql pdo_pgsql \
    mbstring exif pcntl bcmath \
    opcache zip \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear el usuario 1000:1000 para que coincida con docker-compose
RUN addgroup --gid 1000 app \
 && adduser --uid 1000 --ingroup app --disabled-password --gecos "" app

WORKDIR /var/www/html

# Ejecutar como el usuario no-root (evita "I have no name!")
USER 1000:1000

EXPOSE 9000
CMD ["php-fpm"]
