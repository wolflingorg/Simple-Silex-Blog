#!/usr/bin/env bash

##
# Shell provisioner for LAMP
# Tested on ubuntu/xenial64 version 20170626.0.0
##

# PHP 7.1 repository
apt-get install -y python-software-properties
add-apt-repository -y ppa:ondrej/php

# Update package mirrors and update base system
apt-get update -y
apt-get -y dist-upgrade

# Install packages
apt-get install -y mc
apt-get install -y apache2
apt-get install -y php7.1 libapache2-mod-php7.1 php7.1-cli php7.1-common php7.1-mbstring php7.1-gd php7.1-intl php7.1-xml php7.1-mysql php7.1-mcrypt php7.1-zip
apt-get install -y memcached
apt-get install -y php-memcached
apt-get install -y git curl

# Install composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install MySQL Server in a Non-Interactive mode. Default root password will be "root"
echo "mysql-server mysql-server/root_password password root" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password root" | sudo debconf-set-selections
apt-get -y install mysql-server

# Run the MySQL Secure Installation wizard
mysql_secure_installation

service mysql restart

# Set up vhost
cat > /etc/apache2/sites-available/vagrant.conf <<'EOF'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /vagrant/public

    <Directory />
        Options FollowSymLinks
        AllowOverride All
    </Directory>

    <Directory /vagrant/public/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    LogLevel warn
</VirtualHost>
EOF

# Clean other vhosts
rm /etc/apache2/sites-enabled/*

# Activate vagrant vhost
ln -s /etc/apache2/sites-available/vagrant.conf /etc/apache2/sites-enabled/000-vagrant.conf

# Change user and group for apache
sed -i '/APACHE_RUN_USER/d' /etc/apache2/envvars
sed -i '/APACHE_RUN_GROUP/d' /etc/apache2/envvars

cat >> /etc/apache2/envvars <<'EOF'
# Apache user and group
export APACHE_RUN_USER=ubuntu
export APACHE_RUN_GROUP=ubuntu
EOF

# Fix premissions
if [ -d /var/lock/apache2 ]
	then
		chown -R ubuntu:ubuntu /var/lock/apache2
fi

# Enable rewrites
a2enmod rewrite

# Sphinx search
apt-get install -y sphinxsearch

# Clean up
apt-get clean

# Restart services
service apache2 restart