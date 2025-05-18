#!/bin/bash

php artisan key:generate --force
php artisan config:cache

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
