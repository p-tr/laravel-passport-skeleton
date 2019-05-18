#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
#
# If you have user-specific configurations you would like
# to apply, you may also create user-customizations.sh,
# which will be run after this script.

# FIX 502 bad gateway error (comes with nginx 1.15.x)
sudo sed -i 's/\.homestead.test/homestead.test/g' /etc/nginx/sites-available/homestead.test
sudo systemctl restart nginx
sudo systemctl restart php7.3-fpm
sudo systemctl restart php7.2-fpm
sudo systemctl restart php7.1-fpm

# migrate and seed database for development
(cd /home/vagrant/code && {
    composer install
    php artisan app:reset
})
