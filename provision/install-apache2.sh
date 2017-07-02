#!/bin/bash

printf "#################################\n### Installing Apache 2\n#################################\n"

# Apache 2
apt-get install -y apache2

# Set up vhost
cp ${PROJECT_DIR}/provision/config/apache_vhost.conf /etc/apache2/sites-available/vagrant.conf

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