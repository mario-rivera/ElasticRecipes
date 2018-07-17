FROM php:7.0-apache

RUN a2enmod rewrite
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
git

# install php extensions
RUN docker-php-ext-install mysqli \
pdo_mysql

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
&& php -r "unlink('composer-setup.php');"

RUN rm -rf /var/lib/apt/lists/*
