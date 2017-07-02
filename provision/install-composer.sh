#!/bin/sh

printf "#################################\n### Installing Composer\n#################################\n"

# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer