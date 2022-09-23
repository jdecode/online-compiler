FROM php:8.1-apache

#Install vim
RUN apt-get update && apt-get install -y vim

# Install java and javac
RUN apt-get install default-jre default-jdk -y

# Install python and python3
RUN apt-get install python python3 -y


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Install zip+icu dev libs, wget, git
RUN apt-get install libzip-dev zip libicu-dev libpng-dev wget git -y

#Install PHP extensions zip and intl (intl requires to be configured)
RUN docker-php-ext-install zip && docker-php-ext-configure intl && docker-php-ext-install intl exif gd

#PostgreSQL
RUN apt-get update
RUN apt-get install libpq-dev -y

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Set Apache webroot to "public" folder (for Laravel support)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf




# INSTALL NODE JS [16.x]
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs
