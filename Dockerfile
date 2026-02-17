############################################
# Node Image
############################################
FROM node:22-alpine AS frontend


WORKDIR /app

# install deps first (better cachig)

COPY package*.json ./

RUN npm ci

# COPY the rest of the application code
COPY . .

# build the assets
RUN npm run build
############################################
# PRODUCTION IMAGE
############################################

# Learn more about the Server Side Up PHP Docker Images at:
# https://serversideup.net/open-source/docker-php/
FROM serversideup/php:8.4-frankenphp-alpine AS production

USER root

# install php extensions you need
RUN install-php-extensions gd imagick bcmath exif


COPY --chown=www-data:www-data . /var/www/html
COPY --chown=www-data:www-data --from=frontend /app/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader && \
    composer clear-cache && \
    composer dump-autoload

RUN rm -rf /var/www/html/node_modules && \
    rm -rf /var/www/html/public/hot && \
    rm -rf /var/www/html/.github && \
    rm -rf /var/www/html/.gitignore && \
    rm -rf /var/www/html/.gitattributes && \
    rm -rf /var/www/html/docker-compose*.yml && \
    rm -rf /var/www/html/Dockerfile* && \
    rm -rf /var/www/html/README.md && \
    rm -rf /var/www/html/.env.example && \
    rm -rf /var/www/html/.env &&\
    rm -rf /var/www/html/bootstrap/cache/*.php

USER www-data