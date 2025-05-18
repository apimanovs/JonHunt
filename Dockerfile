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

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN COMPOSER_ALLOW_SUPERUSER=1 \
    composer install --no-dev --no-scripts

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV VITE_PUSHER_APP_KEY=GoofNBCH
ENV VITE_PUSHER_APP_CLUSTER=mt1
ENV VITE_PUSHER_HOST=jonhunt.onrender.com
ENV VITE_PUSHER_PORT=6001
ENV VITE_PUSHER_SCHEME=https


RUN echo "VITE_PUSHER_APP_KEY=$VITE_PUSHER_APP_KEY" >> .env && \
    echo "VITE_PUSHER_APP_CLUSTER=$VITE_PUSHER_APP_CLUSTER" >> .env && \
    echo "VITE_PUSHER_HOST=$VITE_PUSHER_HOST" >> .env && \
    echo "VITE_PUSHER_PORT=$VITE_PUSHER_PORT" >> .env && \
    echo "VITE_PUSHER_SCHEME=$VITE_PUSHER_SCHEME" >> .env

RUN npm install && npm run build

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
