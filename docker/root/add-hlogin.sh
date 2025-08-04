#!/bin/sh

composer require --working-dir=/hleb phphleb/hlogin
php /hleb/console phphleb/hlogin add
composer dump-autoload --working-dir=/hleb
php /hleb/console hlogin/create-login-table
php /hleb/console hlogin/create-admin
php /hleb/console --clear-cache
sh /root/console-shell.sh --clear-routes-cache
