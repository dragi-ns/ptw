FROM php:7.4.33-apache

# Install git
RUN apt-get -y update
RUN apt-get -y install git

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# apache2 default configuration
COPY ./apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy application files to the default working directory
COPY ./cakephp .

# Create a tmp directory if doesn't exist
RUN mkdir -p ./app/tmp

# Make that directory accessible by web server
RUN chown -R www-data:www-data ./app/tmp
