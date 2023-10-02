FROM php:7.4-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get upgrade -y 
COPY . /var/www/html
RUN a2enmod rewrite