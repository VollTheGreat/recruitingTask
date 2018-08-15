#!/usr/bin/env sh
set -e

echo "Running PHP FPM"
php-fpm -D | tail -f $LOG_STREAM
