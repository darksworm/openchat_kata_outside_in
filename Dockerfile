FROM php:8-alpine

WORKDIR /var/www

RUN apk add autoconf build-base
RUN pecl install swoole
RUN echo "extension=swoole.so" >> /usr/local/etc/php/php.ini

RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD ["php", "artisan", "swoole:http", "start"]
