FROM php:8.2-fpm-alpine

# These lines are required to build application to be able to use it without repository code.
WORKDIR /app
COPY . /app

# Update package list
RUN apk update
RUN apk upgrade -q -U -a
RUN apk add bash

RUN curl -s https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer self-update

# And also dependencies should be installed after copying files.
Run composer update