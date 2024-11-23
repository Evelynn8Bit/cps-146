# Use the official PHP 8.2 Apache image
FROM php:8.2-apache

# Set the working directory to /app
WORKDIR /app

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the app directory to /app
COPY app /app

# Set the correct ownership and permissions for the app directory
RUN chown -R www-data:www-data /app && chmod -R 755 /app

# Update the Apache configuration to serve content from /app/public
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /app/public\n\
    <Directory /app/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite

# Run Composer install to set up dependencies
RUN composer install --no-interaction --prefer-dist

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
