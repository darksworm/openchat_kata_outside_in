FROM php:8-fpm-alpine

WORKDIR /var/www

RUN apk add autoconf build-base
RUN docker-php-ext-install mysqli pdo pdo_mysql
