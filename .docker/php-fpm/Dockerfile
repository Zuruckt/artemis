FROM phpdockerio/php:8.4-fpm
WORKDIR "/var/www"

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer