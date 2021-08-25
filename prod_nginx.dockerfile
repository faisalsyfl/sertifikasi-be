FROM nginx:stable-alpine

ADD ./nginx/prod/nginx.conf /etc/nginx/
ADD ./nginx/prod/default.conf /etc/nginx/conf.d/

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN chown laravel:laravel /var/www/html