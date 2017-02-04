FROM php:7.1-apache

WORKDIR /var/www

COPY etc/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

COPY . /var/www
