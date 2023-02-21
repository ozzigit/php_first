FROM php:8.1.0-apache

ARG HOST_UID=1000

VOLUME ["/var/www/html"]

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
	&& apt-get install -y libmcrypt-dev \
	&& rm -rf /var/lib/apt/lists/* \
	&& pecl install mcrypt \
	&& docker-php-ext-enable mcrypt
RUN apt-get update && apt-get install -my wget gnupg
RUN apt-get update && apt-get install -y apt-utils
RUN apt-get install -y \
        zlib1g-dev \
        libicu-dev \
        libpq-dev \
        libpcre3-dev \
        git \
        nano \
        libzip-dev \
        zip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        supervisor \
        default-mysql-client \
        cron \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install intl zip pdo_mysql \
    && docker-php-ext-install exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install grpc
RUN pecl install protobuf

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update && apt-get install -y nodejs

COPY ./.bashrc /root/.bashrc
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite remoteip

# Install composer
RUN set -x && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Setup permissions for users `www-data`
RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data && chsh -s /bin/bash www-data

# Setup cron
ADD ./crontab /etc/cron.d/cron-jobs
RUN chmod 0644 /etc/cron.d/cron-jobs && touch /var/log/cron.log

EXPOSE 80
