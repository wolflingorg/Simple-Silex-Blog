#!/bin/bash

##
# Shell provisioner for LAMP
# Tested on ubuntu/xenial64 version 20170626.0.0
##

export PROJECT_DIR=${PROJECT_DIR}

# parse parameters from parameters.yml
DB_PASSWORD=$(sed -n '/database_password/p' ${PROJECT_DIR}/app/config/parameters.yml | sed -e 's/ //g' | awk -F':' '{print $2}')
DB_NAME=$(sed -n '/database_name/p' ${PROJECT_DIR}/app/config/parameters.yml | sed -e 's/ //g' | awk -F':' '{print $2}')

# Update package mirrors and update base system
apt-get update -y
apt-get -y dist-upgrade

# Install Apache2
source "$PROJECT_DIR/provision/install-apache2.sh"

# Install PHP
source "$PROJECT_DIR/provision/install-php7.1.sh"

# Install packages
apt-get install -y mc
apt-get install -y memcached
apt-get install -y git
apt-get install -y curl

apt-get install -y php-memcached

# Install composer
source "$PROJECT_DIR/provision/install-composer.sh"

# Install MySQL
source "$PROJECT_DIR/provision/install-mysql.sh"

# Sphinx search
source "$PROJECT_DIR/provision/install-sphinxsearch.sh"

# Clean up
apt-get clean

# Restart services
service apache2 restart

# Install PHP
source "$PROJECT_DIR/provision/app-commands.sh"