FROM php:7.2.2-fpm-alpine

ARG XDEBUG_PORT=172.18.0.1

ENV LOG_STREAM="/tmp/stdout"

WORKDIR /var/www

COPY ./develop/php.ini /usr/local/etc/php/
COPY ./develop/xdebug.ini /usr/local/etc/php/conf.d/
COPY ./develop/entrypoint.sh /usr/local/bin/

RUN set -ex \
    && mkfifo ${LOG_STREAM} \
    && chmod 777 ${LOG_STREAM} \
    && apk add --no-cache freetype git libjpeg-turbo libpng unzip zip zlib-dev imagemagick-dev libzip \
    && apk add --no-cache --virtual build-deps alpine-sdk autoconf freetype-dev libpng-dev libjpeg-turbo-dev libtool libzip-dev \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install bcmath gd pdo_mysql zip \
    && docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && pecl install imagick rar xdebug \
    && docker-php-ext-enable imagick rar xdebug \
    && printf "xdebug.remote_host=${XDEBUG_PORT}\n" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
#    \
#    && composer global require hirak/prestissimo

CMD entrypoint.sh

RUN set -ex \
    && composer global require hirak/prestissimo \
