FROM php:7.4

WORKDIR /app

COPY --from=composer:1.10.10 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    zip git libffi-dev \
    && docker-php-ext-install ffi
