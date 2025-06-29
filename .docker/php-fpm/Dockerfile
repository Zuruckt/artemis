FROM phpswoole/swoole:php8.4-alpine

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && chmod +x composer.phar \
    && mv composer.phar /usr/bin/composer

RUN apk --update --no-cache add \
    git make autoconf build-base linux-headers

RUN set -ex && \
    rm -f "$(php-config --ini-dir)/docker-php-ext-pdo_mysql.ini" && \
    rm -f "$(php-config --ini-dir)/docker-php-ext-redis.ini" && \
    pecl install xdebug ds && \
    docker-php-ext-enable xdebug ds

EXPOSE 9000

ENTRYPOINT ["php", "public/index.php", "start"]
