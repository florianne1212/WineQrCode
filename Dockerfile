FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required system dependencies
RUN apt-get update \
  && apt-get install -y libzip-dev git wget \
                         libpng-dev libjpeg-dev libfreetype6-dev \
                         --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions
RUN docker-php-ext-install pdo mysqli pdo_mysql zip gd

# Install Composer
RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

# Install MySQL client (if needed)
RUN apt-get update && apt-get install -y default-mysql-client

# Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh

# Copy application files
COPY . /var/www

# Set working directory
WORKDIR /var/www

# Set executable permissions on entrypoint script
RUN chmod +x /entrypoint.sh

# Install application dependencies without running scripts or autoloader
COPY composer.json composer.lock ./
RUN composer self-update --2
RUN composer update --no-scripts --no-autoloader
RUN composer install
RUN mkdir -p /var/www/migrations
RUN mkdir -p /var/www/migrations
RUN mkdir -p /var/www/public/uploads/wine_image
RUN mkdir -p /var/www/public/uploads/winery_image
RUN chown -R www-data:www-data /var/www/public/uploads



# Set the entrypoint script
ENTRYPOINT ["/entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
