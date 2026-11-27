#!/bin/sh
set -e

cd /var/www/html

if [ -z "${APP_KEY:-}" ] && [ -f artisan ]; then
    export APP_KEY="$(php artisan key:generate --show)"
fi

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    touch database/database.sqlite
fi

if [ "${AUTO_MIGRATE:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan optimize

exec "$@"
