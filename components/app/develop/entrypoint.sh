#!/usr/bin/env sh
set -e
echo "Starting entrypoint.sh"
if [[ -f composer.json ]]; then
    echo "Installing dependencies"
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan config:clear
    echo "Chmoding cache and storage folders"
    chmod -R 777 /var/www/bootstrap/cache /var/www/storage
fi

echo "Running PHP FPM"
php-fpm -D | tail -f $LOG_STREAM
