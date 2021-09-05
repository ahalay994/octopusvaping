FROM php:8.0.5-fpm

ARG PHPGROUP
ARG PHPUSER

ENV PHPGROUP=${PHPGROUP}
ENV PHPUSER=${PHPUSER}

RUN adduser -g ${PHPGROUP} -s /bin/sh -D ${PHPUSER}; exit 0

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install pdo pdo_mysql

# Update packages
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs netcat libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev nodejs git \
    && apt-get clean

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
