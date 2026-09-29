# PHP + Apache runtime used by the existing application.
FROM php:8.2-apache

# Install the MySQLi extension required by the existing PHP code.
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Copy the application into Apache's document root.
COPY . /var/www/html/
RUN chmod +x /var/www/html/render-entrypoint.sh

# Render routes HTTP traffic to the container's Apache port.
EXPOSE 80

# Initialize the Render MySQL database, then start Apache.
ENTRYPOINT ["/var/www/html/render-entrypoint.sh"]
