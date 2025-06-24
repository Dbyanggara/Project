#!/bin/sh

# Start PHP-FPM
php-fpm -D

# Execute the main container command (nginx)
exec "$@"
