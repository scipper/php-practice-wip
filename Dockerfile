FROM composer:2.7.6 as composer

WORKDIR /app

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist

COPY . .
RUN composer dump-autoload

FROM php:8.3.6-apache

ARG HOME=/var/www/html

COPY config/virtual-host.conf /etc/apache2/sites-available/virtual-host.conf
COPY src/main/php $HOME/src/main/php

RUN echo "ServerName phpinjector" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    a2dissite 000-default && \
    a2ensite virtual-host && \
    service apache2 restart

WORKDIR $HOME

COPY --from=composer app/vendor/ ./vendor/

EXPOSE 80