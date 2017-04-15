FROM nofw/base

# Install dependencies
COPY composer.json composer.lock /var/www/
RUN composer install --prefer-dist --no-dev --no-interaction

COPY . /var/www

ENV APP_ENV prod
RUN mkdir -p var/cache && bin/cache
