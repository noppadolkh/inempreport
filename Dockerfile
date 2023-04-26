# Use a builder image for installing Composer dependencies
FROM composer:2 as builder
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts

# Use the official PHP 8.0 Apache base image
FROM php:8.0-apache

# Install common PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install additional packages
RUN apt-get update && apt-get install -y \
    some-package \
    another-package

# Clean up temporary files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy the custom php.ini file
COPY php.ini /usr/local/etc/php/

# Copy your application source code and vendor directory from the builder image
COPY --from=builder /app/vendor /var/www/html/vendor
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Expose the default web server port (80)
EXPOSE 80
