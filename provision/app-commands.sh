#!/bin/sh

printf "#################################\n### Running app scripts\n#################################\n"

# Composer
composer install -d ${PROJECT_DIR}

# Creating DB
php ${PROJECT_DIR}/app/console dbal:run-sql "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# Migrations
php ${PROJECT_DIR}/app/console migrations:migrate --no-interaction
