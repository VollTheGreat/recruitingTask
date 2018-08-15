#!/usr/bin/env sh
set -e
echo "Starting entrypoint.sh"
if [[ -f composer.json ]]; then
    echo "Installing dependencies"
    composer install
fi
cp .env.example .env

php artisan key:generate

echo "Removing config cache"
php artisan config:cache

echo "Chmoding cache and storage folders"
chmod -R 777 /var/www/bootstrap/cache /var/www/storage

echo "Running PHP FPM"
php-fpm -D | tail -f $LOG_STREAM
