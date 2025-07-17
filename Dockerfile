# Use an official PHP image as a base
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install MySQLi extension for PHP
# docker-php-ext-install is a helper script provided by the official PHP images
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy the application source code into the container
COPY src/ .

# Expose port 80 for the web server
EXPOSE 80

# The default command for php:apache images starts Apache
# CMD ["apache2-foreground"]
