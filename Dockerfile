FROM php:8.3.6-apache

ENV HOME = /var/www/html
COPY . $HOME

WORKDIR $HOME
EXPOSE 80