FROM php:8.0-apache
RUN apt-get update
RUN apt-get install -y \
        zip 
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/
COPY composer.json composer.lock ./
RUN composer install --prefer-source --no-interaction
WORKDIR /var/www/html
