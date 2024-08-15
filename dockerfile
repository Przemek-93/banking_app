FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    unzip

WORKDIR /var/www
COPY . /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --verbose