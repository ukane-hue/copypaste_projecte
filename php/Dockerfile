#FROM php:8.2-fpm

# Instal·lar paquets addicionals
#RUN docker-php-ext-install mysqli pdo_mysqli

# Definir el directori de treball
#WORKDIR /var/www/html
FROM php:8.2-fpm

# Instal·lar paquets addicionals i extensions PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
 && docker-php-ext-install mysqli pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# Definir el directori de treball
WORKDIR /var/www/html


