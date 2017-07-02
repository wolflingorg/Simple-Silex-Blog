#!/bin/sh

printf "#################################\n### Installing PHP 7.1\n#################################\n"

# PHP 7.1 repository
add-apt-repository -y ppa:ondrej/php

apt-get update -y
apt-get -y dist-upgrade

apt-get install -y php7.1 libapache2-mod-php7.1 php7.1-cli php7.1-common php7.1-mbstring php7.1-gd php7.1-intl php7.1-xml php7.1-mysql php7.1-mcrypt php7.1-zip php7.1-curl
apt-get install php-xdebug

# Some changes to php.ini
sed -i 's/display_errors = Off/display_errors = On/g' /etc/php/7.1/apache2/php.ini
sed -i 's/display_startup_errors = Off/display_startup_errors = On/g' /etc/php/7.1/apache2/php.ini
sed -i 's/track_errors = Off/track_errors = On/g' /etc/php/7.1/apache2/php.ini