FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev

RUN npm install --prefix frontend

RUN npm run build --prefix frontend

RUN php artisan config:cache

RUN php artisan migrate --force --seed

WORKDIR /var/www/backend

EXPOSE 8000 6001

CMD ["sh", "-c", "php artisan serve --host 0.0.0.0 --port 8000 & php artisan websockets:serve"]
