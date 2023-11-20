# Use the official PHP image with PHP 8.1
FROM php:8.1-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    && docker-php-ext-install pdo_mysql gd zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the local code to the container
COPY . .

# Install PHP dependencies
RUN composer install --no-scripts

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
