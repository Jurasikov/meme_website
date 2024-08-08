FROM php:8.1.4-apache

COPY ./composer.* ./

RUN apt-get update && apt-get install -y git zip unzip 
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 16M/' $PHP_INI_DIR/php.ini-development && \
    mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer
RUN composer install && composer dump-autoload