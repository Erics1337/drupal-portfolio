FROM drupal:10-apache

# Install nano and unzip (optional tools)
RUN apt-get update && apt-get install -y nano unzip

# Install Drush using Composer
RUN composer global require drush/drush

# Add Composer's global bin to the PATH
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Copy custom Apache config file
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache rewrite module for Drupal clean URLs
RUN a2enmod rewrite

# Set the working directory to the web root
WORKDIR /var/www/html
