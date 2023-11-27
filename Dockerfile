FROM php:8.1-fpm-bullseye

RUN pecl install redis-5.3.7 \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable redis xdebug \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

STOPSIGNAL SIGQUIT

WORKDIR /app

CMD php-fpm