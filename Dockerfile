FROM php:8.0-cli

WORKDIR /app

COPY --from=composer:2.0.11 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    zip git libffi-dev \
    && docker-php-ext-install ffi