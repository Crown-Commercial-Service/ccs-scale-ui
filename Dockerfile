FROM php:7.3.8-apache

#set aplication directory
WORKDIR  /var/www/html/ccs

RUN apt-get update
RUN apt-get install -y  git unzip zip curl

# install node/npm
RUN curl -sL https://deb.nodesource.com/setup_11.x  | bash -
RUN apt-get -y install nodejs

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Apache
ENV APACHE_DOCUMENT_ROOT = /var/www/html/ccs/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/ccs/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/ccs/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN a2enmod php7
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

#copy project into container
COPY ./ ./

# increase memory limit to 2GB
RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

# run composer update
RUN composer update

#install javascript modules
RUN npm install

# restart apache
RUN service apache2 restart

VOLUME /var/www/html/ccs/public

#set container port
EXPOSE 80

# usage :
# docker build .
# docker run -p 8080:80 #idcontainer