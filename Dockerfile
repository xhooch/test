# syntax=docker/dockerfile:experimental
FROM php:8.1-cli as builder

LABEL name=php8.1-cli-composer project=auction category=test-task

ENV DEBIAN_FRONTEND=noninteractive DEBCONF_NONINTERACTIVE_SEEN=true

RUN apt-get update && apt-get install -y locales git zip curl gnupg lsb-release

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer global config minimum-stability dev && \
    composer global config secure-http false && \
    composer global config repo.packagist composer https://packagist.org

RUN apt-get update

COPY common /auction-app/common
COPY console /auction-app/console
COPY console /auction-app/frontend
COPY docker/configs/main-local.common.php /auction-app/common/config/main-local.php
COPY docker/configs/main-local.frontend.php /auction-app/frontend/config/main-local.php
COPY composer.json /auction-app/composer.json
COPY composer.lock /auction-app/composer.lock
COPY yii /auction-app/yii
COPY yii_test /auction-app/yii_test

WORKDIR /auction-app

RUN composer install --no-interaction --no-progress --optimize-autoloader --prefer-dist --ignore-platform-reqs -v && \
    rm -rf ./docker ./composer*

FROM php:8.1-fpm-alpine3.17

LABEL name=php8.1-fpm-nginx-alpine project=auction category=test-task

RUN apk update && \
    apk upgrade

RUN apk add --no-cache alpine-sdk autoconf supervisor nginx icu-dev

RUN docker-php-ext-install -j$(nproc) pcntl bcmath intl pdo_mysql sockets

RUN mkdir -p /run/nginx && \
    mkdir -p /var/log/php && \
    touch /var/log/php/error.log && \
    chown -R www-data:www-data /var/log/php

RUN apk upgrade && \
    mkdir -p /run/nginx && \
    rm /etc/nginx/http.d/default.conf && \
    rm /etc/supervisord.conf && \
    mkdir -p /var/log/php /var/log/auction-app && \
    touch /var/log/php/error.log && \
    chown -R www-data:www-data /var/log/php /var/log/auction-app

COPY --from=builder /auction-app /auction-app
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/nginx.conf /etc/nginx/http.d/nginx.conf
COPY docker/php.ini /usr/local/etc/php/
COPY docker/startup.sh /root/startup.sh
COPY docker/cronfile /root/cronfile

# Install dockerize
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

RUN chmod +x /root/startup.sh && \
    chmod +r /root/cronfile && \
    chown -R www-data:www-data \
        /auction-app/frontend/runtime \
        /auction-app/console/runtime

WORKDIR /auction-app
EXPOSE 80
LABEL name=auction-app project=test-task category=backend

CMD /root/startup.sh
