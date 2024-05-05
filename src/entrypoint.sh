#!/bin/bash
composer install
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
. /opt/bitnami/scripts/laravel/entrypoint.sh
. /opt/bitnami/scripts/laravel/run.sh
exec "$@"