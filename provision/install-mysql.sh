#!/bin/sh

printf "#################################\n### Installing MySQL\n#################################\n"

# Install MySQL Server in a Non-Interactive mode
echo "mysql-server mysql-server/root_password password $DB_PASSWORD" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $DB_PASSWORD" | debconf-set-selections
apt-get -y install mysql-server

service mysql restart

mysql -uroot -p${DB_PASSWORD} -e 'USE mysql; UPDATE `user` SET `Host`="%" WHERE `User`="root" AND `Host`="localhost"; DELETE FROM `user` WHERE `Host` != "%" AND `User`="root"; FLUSH PRIVILEGES;'