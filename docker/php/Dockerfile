FROM php:8.2-fpm-alpine

# system deps
RUN apk add --no-cache \
    git \
    curl \
    bash \
    icu-data-full \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    zlib-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    $PHPIZE_DEPS

# configure and install gd with all options
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        opcache \
        gd \
        pcntl \
        bcmath 

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# runtime user
RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www && \
    chown -R www:www /var/www/html
USER www
