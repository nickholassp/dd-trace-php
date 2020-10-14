FROM php:7.3-fpm

RUN apt-get update \
    && apt-get install -y tcpdump wget gnupg2 procps \
    && apt-get clean

RUN docker-php-ext-install opcache
RUN docker-php-ext-install mysqli

RUN pecl install redis
ADD redis.ini /usr/local/etc/php/conf.d/redis.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /coredumps
RUN chown -R www-data:www-data /coredumps

ADD entrypoint.sh /entrypoint.sh

CMD /entrypoint.sh
