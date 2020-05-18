FROM php:7.3-apache


ENV PORT 9031

# set work directory
 WORKDIR  /var/www/html/wordpress

RUN apt-get update

RUN apt-get install -y  git unzip zip curl git 


# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#get WordPress project from git
RUN git clone https://github.com/Crown-Commercial-Service/ccs-wordpress.git
COPY ./.env ./
WORKDIR  /var/www/html/wordpress/ccs-wordpress

RUN  composer install 
RUN cp /var/www/html/wordpress/.env  /var/www/html/wordpress/ccs-wordpress/.env

# Configure Apache
ENV APACHE_DOCUMENT_ROOT = /var/www/html/wordpress/ccs-wordpress/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/wordpress/ccs-wordpress/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/wordpress/ccs-wordpress/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


RUN a2enmod rewrite
RUN a2enmod php7
RUN echo "ServerName localhost:$PORT" >> /etc/apache2/apache2.conf

# increase memory limit to 2GB
# RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN service apache2 restart

# VOLUME /var/www/html/wordpress/ccs-wordpress

EXPOSE $PORT
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
