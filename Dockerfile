FROM drupal:10-apache

# Install nano
RUN apt-get update && apt-get install -y nano

# Copy custom Apache config file
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache rewrite module for Drupal clean URLs
RUN a2enmod rewrite

# Set the working directory to the web root
WORKDIR /var/www/html

