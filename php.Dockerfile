FROM php:7.3-apache

RUN apt-get update && apt-get upgrade -y && apt-get install git zip -y
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql