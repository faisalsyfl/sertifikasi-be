FROM php:7.2-fpm-alpine

<<<<<<< master:dev_php.dockerfile
ADD ./php/dev/www.conf /usr/local/etc/php-fpm.d/
=======
ADD ./www.conf /usr/local/etc/php-fpm.d/
>>>>>>> coba rollback:php/dev/php.dockerfile

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
