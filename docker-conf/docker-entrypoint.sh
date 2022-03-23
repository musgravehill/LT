#!/bin/bash

echo "Hello from ENTRYPOINT"
cd /var/www/lt && composer update 
php-fpm -F