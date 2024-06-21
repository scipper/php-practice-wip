FROM composer:2.7.6 as composer

WORKDIR /app

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative \
    --apcu-autoloader

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --apcu

FROM php:8.3.6-apache-bullseye

ARG HOME=/var/www/html

COPY config/virtual-host.conf /etc/apache2/sites-available/virtual-host.conf

RUN echo "ServerName phpinjector" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    a2dissite 000-default && \
    a2ensite virtual-host && \
    service apache2 restart

RUN pecl install xdebug \
    && apt update \
    && apt install libzip-dev -y \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && rm -rf /var/lib/apt/lists/*

COPY config/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR $HOME

COPY --from=composer app/vendor/ ./vendor/

EXPOSE 80