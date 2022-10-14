ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.18
#ARG POSTGRES_VERSION=14.5

ARG WORKDIR=/app


FROM composer:2 as composer
FROM php:${PHP_VERSION}-fpm-alpine AS base

ARG USER_UID=82
ARG USER_GID=82

#RUN set -ex \
#  && apk --no-cache add \
#    postgresql-dev
#
#RUN docker-php-ext-install pdo pdo_pgsql

# Recreate www-data user with user id matching the host
RUN deluser --remove-home www-data && \
    addgroup -S -g ${USER_GID} www-data && \
    adduser -u ${USER_UID} -D -S -G www-data www-data

# Necessary tools
RUN apk add --update --no-cache ${PHPIZE_DEPS} git curl

# ZIP module
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip

# postgres module
RUN apk add --no-cache postgresql-dev && docker-php-ext-install pdo pdo_pgsql

# gd module
#RUN apt-get update && apt-get -y install libjpeg-dev libpng-dev zlib1g-dev git zip
#RUN docker-php-ext-configure gd \
#        --with-png-dir=/usr/include \
#        --with-jpeg-dir=/usr/include \
#    && docker-php-ext-install gd \
#    && docker-php-ext-enable gd \

#RUN apk add --no-cache libjpeg-dev libpng-dev zlib1g-dev && docker-php-ext-install gd
#RUN docker-php-ext-configure gd \
#        --with-png-dir=/usr/include \
#        --with-jpeg-dir=/usr/include \
#    && docker-php-ext-install gd \
#    && docker-php-ext-enable gd \

RUN apk add --no-cache msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu
# https://github.com/rhamdeew/docker-php-8-fpm-alpine/blob/master/Dockerfile
RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    docker-php-ext-install intl && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    docker-php-ext-install zip && \
    apk del build-essentials
#    && rm -rf /usr/src/php* \

# Imagick module
#RUN apk add --no-cache libgomp imagemagick imagemagick-dev && \
#	pecl install -o -f imagick && \
#	docker-php-ext-enable imagick
#
## Symfony CLI tool
#RUN apk add --no-cache bash && \
#	curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
#	apk add symfony-cli && \
#	apk del bash
#
# XDebug from PECL
RUN pecl install xdebug-3.1.5

# Necessary build deps not longer needed
RUN apk del --no-cache ${PHPIZE_DEPS} \
    && docker-php-source delete

# Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# XDebug wrapper
COPY ./artifacts/xdebug /usr/local/bin/xdebug
RUN chmod +x /usr/local/bin/xdebug

# Clean up image
RUN rm -rf /tmp/* /var/cache
