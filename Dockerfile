FROM php:8.1-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    curl \
    git \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl mysqli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN composer install --optimize-autoloader --no-dev

# RUN sed -i 's!ErrorLog ${APACHE_LOG_DIR}/error.log!ErrorLog /dev/stderr!' /etc/apache2/apache2.conf && \
#     sed -i 's!CustomLog ${APACHE_LOG_DIR}/access.log combined!CustomLog /dev/stdout combined!' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]

# # Use a builder image for installing Composer dependencies
# FROM composer:2 as builder
# COPY composer.json composer.lock ./
# RUN composer install --no-dev --no-scripts

# # Use the official PHP 8.0 Apache base image
# FROM php:8.0-apache

# # Install common PHP extensions
# RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# # Enable Apache mod_rewrite
# RUN a2enmod rewrite

# # Install additional packages
# RUN apt-get update && apt-get install -y \
#     some-package \
#     another-package

# # Clean up temporary files
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# # Copy the custom php.ini file
# COPY php.ini /usr/local/etc/php/

# # Copy your application source code and vendor directory from the builder image
# COPY --from=builder /app/vendor /var/www/html/vendor
# COPY . /var/www/html/

# # Set the working directory
# WORKDIR /var/www/html

# # Expose the default web server port (80)
# EXPOSE 80
