#!/usr/bin/env bash

# Copy over testing configuration.
cp .env.example .env

# Generate an application key. Re-cache.
php artisan -V
php artisan env
php artisan key:generate
php artisan migrate:refresh --seed
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan storage:link

php vendor/bin/phpunit --colors=never --coverage-text --filter Feature
