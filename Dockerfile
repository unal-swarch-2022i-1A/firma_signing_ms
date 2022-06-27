FROM php:8.0-apache
LABEL org.opencontainers.image.source https://github.com/unal-swarch-2022i-1A/firma_signing_ms
RUN apt-get update
RUN apt-get install -y \
        zip 
RUN docker-php-ext-install sockets
RUN a2enmod rewrite
RUN service apache2 restart        
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/
COPY composer.json composer.lock ./
COPY src/* ./src/
RUN composer install --prefer-source --no-interaction
COPY .env ./
WORKDIR /var/www/html
COPY ./public /var/www/html/ 
COPY ./src /var/www/src/ 
COPY ./tests /var/www/tests/ 
