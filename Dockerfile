FROM drupal:10-apache

# Install nano and unzip for editing and extraction purposes (optional)
RUN apt-get update && apt-get install -y nano unzip

# Install Drush globally using Composer
RUN composer global require drush/drush

# Add Composer's global bin to the PATH for Drush to be available globally
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Enable Apache rewrite module for Drupal clean URLs
RUN a2enmod rewrite

# Copy custom Apache config file if needed
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set the working directory to the web root
WORKDIR /var/www/html
