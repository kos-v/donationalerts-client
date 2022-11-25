FROM php:7.4-fpm-alpine

ENV BUILD_DEPS \
    autoconf \
    bash \
    build-base \
    git \
    pcre-dev

RUN apk add --no-cache --virtual .build-deps ${BUILD_DEPS} \
    && docker-php-ext-install pcntl opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

CMD php-fpm