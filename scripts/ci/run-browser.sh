#!/usr/bin/env bash

cp .env.example .env

## Custom commands from the images used
configure-laravel
start-nginx-ci-project

php artisan dusk --colors --debug