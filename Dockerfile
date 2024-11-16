FROM drupal:10-apache

# Install nano and unzip for editing and extraction purposes (optional)
RUN apt-get update && apt-get install -y nano unzip

# Install Drush globally using Composer
RUN composer global require drush/drush

# Add Composer's global bin to the PATH for Drush to be available globally
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Enable Apache rewrite module for Drupal clean URLs
RUN a2enmod rewrite

# Set the working directory to the project root (where composer.json is located)
WORKDIR /var/www/html

# Copy the entire project to the container
COPY . /var/www/html

# Install Composer dependencies, including Drupal core
RUN composer install

# Set the working directory to the web root
WORKDIR /var/www/html/web
