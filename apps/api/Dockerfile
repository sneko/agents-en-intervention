FROM php:8.2.8-fpm-alpine3.18 AS base

RUN apk add --no-cache git gnupg libpq-dev libzip-dev icu-dev tzdata
RUN docker-php-ext-install pdo pdo_pgsql intl zip opcache
ENV TZ="Europe/Paris"
RUN printf '[PHP]\ndate.timezone = ${TZ}\n' > /usr/local/etc/php/conf.d/tzone.ini
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 20M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 120;" >> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

FROM base AS install

COPY --from=composer:2.5.8 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock .
RUN composer install -o --no-dev --no-scripts

FROM install AS assets

RUN touch .env
ENV DATABASE_URL=sqlite3://:memory:
RUN composer install -o --no-scripts
COPY . .
RUN php bin/console assets:install

FROM install AS dev

RUN apk add --no-cache $PHPIZE_DEPS linux-headers
RUN docker-php-ext-install pcntl posix
RUN pecl install xdebug
RUN docker-php-ext-enable pcntl posix xdebug \
    && echo -e "\nxdebug.mode=coverage\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ARG PHIVE_VERSION=0.15.2
RUN wget -O phive.phar https://github.com/phar-io/phive/releases/download/$PHIVE_VERSION/phive-$PHIVE_VERSION.phar \
    && wget -O phive.phar.asc https://github.com/phar-io/phive/releases/download/$PHIVE_VERSION/phive-$PHIVE_VERSION.phar.asc \
    && gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 \
    && gpg --verify phive.phar.asc phive.phar \
    && chmod +x phive.phar \
    && mv phive.phar /usr/local/bin/phive

FROM base

COPY --from=install --chown=www-data /var/www/html/vendor ./vendor
COPY --from=assets --chown=www-data /var/www/html/public/bundles ./public/bundles
COPY --chown=www-data . .
USER www-data
RUN touch .env
ENV APP_ENV=prod
