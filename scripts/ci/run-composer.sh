#!/usr/bin/env bash

# Changing cache path
composer config -g cache-dir "$(pwd)/.composer-cache"

# Run composer installation process
composer install -o --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts