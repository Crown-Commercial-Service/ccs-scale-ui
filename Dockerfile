FROM php:7.4.4-apache

ENV PORT 9030

#set aplication directory
WORKDIR  /var/www/html/ccs

RUN apt-get update
RUN apt-get install -y  git unzip zip curl

# install node/npm
RUN curl -sL https://deb.nodesource.com/setup_12.x  | bash -
RUN apt-get -y install nodejs

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Apache
ENV APACHE_DOCUMENT_ROOT = /var/www/html/ccs/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/ccs/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/ccs/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN a2enmod php7
RUN echo "ServerName localhost:$PORT" >> /etc/apache2/apache2.conf

#COPY ./package.json ./

#COPY ./composer.json ./


# increase memory limit to 2GB
RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

#copy project into container
COPY ./ ./


# run composer update
RUN composer update

#RUN composer dump-env dev

#install javascript modules
RUN npm install


# restart apache
RUN service apache2 restart

VOLUME /var/www/html/ccs

#set container port
EXPOSE $PORT

CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground ; npm run build


# usage :
# docker build .
# docker run  -d  -p  9030:9030 -e PORT=9030 #id_container