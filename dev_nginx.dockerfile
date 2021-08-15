FROM nginx:stable-alpine

ADD ./nginx/dev/nginx.conf /etc/nginx/
ADD ./nginx/dev/default.conf /etc/nginx/conf.d/

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN chown laravel:laravel /var/www/html
