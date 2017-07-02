#!/bin/sh

printf "#################################\n### Running app scripts\n#################################\n"

# Creating DB
php ${PROJECT_DIR}/app/console dbal:run-sql "CREATE DATABASE IF NOT EXISTS $DB_NAME;"