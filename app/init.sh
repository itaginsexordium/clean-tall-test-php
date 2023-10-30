#!/bin/bash

APP_CODE_PATH_CONTAINER=/var/www/html

echo "Install composer dependencies..."
composer install -d "${APP_CODE_PATH_CONTAINER}"

echo "Deal with the .env file if necessary..."
if [ ! -f "${APP_CODE_PATH_CONTAINER}/.env" ]; then
    echo "Create .env file..."
    cp "${APP_CODE_PATH_CONTAINER}/.env.example" "${APP_CODE_PATH_CONTAINER}/.env"

    echo "Generate application key"
    php "${APP_CODE_PATH_CONTAINER}/artisan" key:generate --ansi
fi

if [ ! -L "${APP_CODE_PATH_CONTAINER}/public/storage" ]; then
    echo "Storage link..."
    php "${APP_CODE_PATH_CONTAINER}/artisan" storage:link
fi



