# docker image base
ARG BASE_NGINX=nginx:1.21.5-alpine
ARG BASE_PHP=php:8.1.0-fpm-bullseye

FROM $BASE_NGINX as nginx-builder
COPY .docker/nginx/templates/* /etc/nginx/templates/
## php-fpm-alpine-builder image: install common php extension need for project
FROM $BASE_PHP AS php-fpm-alpine-builder
LABEL maintainer="huypq <phanquochuyqthm@gmail.com>"
ENV TZ=Asia/Tokyo
ENV PYTHONUNBUFFERED=1
RUN set -eux; \
        \
        apt update; \
        \
        \
        # https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=841341
        echo 'On debian image based, fix issue "Bad substitution" if /bin/sh is not bash so force point sh to bash'; \
            ln -sf /bin/bash /bin/sh; \
        \
        echo "CHANGE TIME ZONE TO '$TZ' AT BUILD TIME ONLY"; \
            echo "env TZ=$TZ at runtime will auto change docker image timezone"; \
            echo "Test current date: $(date)"; \
        \
        echo "INSTALL OS COMMON SOFTWARES"; \
            apt-get update; \
            apt-get install -y --no-install-recommends \
                        wget \
                        unzip \
                        apt-utils; \
        \
        echo "INSTALL AS ROOT DEBIAN"; \
            apt-get install -y build-essential; \
        echo "INSTALL BIZCRM PHP EXTENSION NEEDED 'pdo_mysql' 'ldap'"; \
            apt-get install -y libldap2-dev libzip-dev; \
            docker-php-ext-install -j "$(nproc)" pdo_mysql mysqli ldap zip; \
        \
         echo "INSTALL xlsx2csv"; \
            apt-get update; \
            apt-get install -y  --no-install-recommends \
            python3\
            python3-pip; \
            pip install xlsx2csv; \
        \
         echo "INSTALL supervisor"; \
            pip install supervisor; \
        echo 'CLEAN UP TEMP TO REDUCE DOCKER IMAGE SIZES'; \
        rm -rf /tmp/*

#####################################################################################################################
## maybe-public: bundle  public source code (js,css). use this image on read-only env (production, staging) ##
#####################################################################################################################
FROM nginx-builder as maybe-public

# tag help inspect docker image in the future
ARG GIT_COMMIT_HASH=unspecified
ARG GIT_BRANCH_NAME=unspecified

LABEL maintainer="huypq <phanquochuyqthm@gmail.com>" \
    git.commit=$GIT_COMMIT_HASH \
    git.branch=$GIT_BRANCH_NAME

WORKDIR /var/www/html/maybe/public
COPY .docker/nginx/conf.d/maybe-app.conf /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=nginx:nginx public .
EXPOSE 80

##########################################################################################################
## maybe-vendor ##
##########################################################################################################
FROM composer:2.3.5 AS vendor
WORKDIR /var/www/html/maybe
COPY --chown=www-data:www-data composer.json artisan ./
RUN set -eux; \
composer install \
        --quiet \
        --ignore-platform-reqs \
        --no-interaction \
        --no-progress \
        --no-suggest \
        --no-scripts \
        --prefer-dist \
        --no-autoloader \
        --no-dev;
        
##########################################################################################################
## php-fpm-dev image: install php extension support for developer
FROM php-fpm-alpine-builder AS php-fpm-dev
ARG USER_ID=1000
ARG GROUP_ID=1000
ENV COMPOSER_CACHE_DIR=/tmp
WORKDIR /var/www/html/maybe
COPY storage storage
RUN set -eux; \
        echo "INSTALL NPM nodejs, Xdebug"; \
            apt-get update; \
            curl -fsSL https://deb.nodesource.com/setup_current.x | bash - && \
            apt-get install -y nodejs; \
            pecl install xdebug-3.1.2; \
            docker-php-ext-enable xdebug; \
        \
        cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
        \
        echo 'CLEAN UP TEMP TO REDUCE DOCKER IMAGE SIZES'; \
        rm -rf /tmp/*
RUN mkdir -p /etc/supervisor/conf.d
COPY .docker/php/conf.d/z-xdebug.dev.ini .docker/php/conf.d/z-php.dev.ini $PHP_INI_DIR/conf.d/
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
COPY .docker/php/workers/*.conf /etc/supervisor/conf.d
CMD ["php-fpm"]
EXPOSE 9000
