FROM php:8.0-apache
LABEL org.opencontainers.image.source https://github.com/unal-swarch-2022i-1A/firma_signing_ms
RUN apt-get update
RUN apt-get install -y \
        zip 
RUN docker-php-ext-install sockets        
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/
COPY composer.json composer.lock ./
COPY src/* ./src/
RUN composer install --prefer-source --no-interaction
COPY .env ./
WORKDIR /var/www/html
