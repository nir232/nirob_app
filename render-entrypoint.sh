#!/bin/sh
set -eu

# Initialize the database before Apache accepts requests.
php /var/www/html/init_db.php

exec apache2-foreground
