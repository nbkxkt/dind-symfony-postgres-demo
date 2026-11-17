FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev unzip \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY .env.example .env

COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --no-scripts

COPY . .
RUN composer dump-autoload --no-dev --classmap-authoritative --no-scripts \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data /app

USER www-data
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
