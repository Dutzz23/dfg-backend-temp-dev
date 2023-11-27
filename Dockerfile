FROM php:8.1-fpm-bullseye

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
      zip \
      unzip \
      libsodium23 \
      libsodium-dev \
      curl \
      git \
      ca-certificates \
      libargon2-1 \
      libargon2-0-dev \
      libonig-dev \
      libcurl4-openssl-dev; \
    pecl install redis-5.3.7 \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable redis xdebug \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install curl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install opcache;

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

STOPSIGNAL SIGQUIT

WORKDIR /app

CMD php-fpm