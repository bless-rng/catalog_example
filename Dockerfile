FROM php:7.4-fpm

RUN apt update
RUN apt install wget curl zip unzip
RUN wget https://getcomposer.org/composer-stable.phar && mv composer-stable.phar /usr/local/bin/composer && chmod 777 /usr/local/bin/composer
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/app