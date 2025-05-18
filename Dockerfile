FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev git unzip supervisor && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www

COPY . .

RUN echo "PUSHER_APP_KEY: $PUSHER_APP_KEY" && \
    echo "PUSHER_APP_SECRET: $PUSHER_APP_SECRET" && \
    echo "PUSHER_APP_ID: $PUSHER_APP_ID" && \
    echo "PUSHER_APP_CLUSTER: $PUSHER_APP_CLUSTER"

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev && \
    composer require beyondcode/laravel-websockets

RUN npm install

RUN npm run build

RUN php artisan key:generate

RUN php artisan config:cache

EXPOSE 8000

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
