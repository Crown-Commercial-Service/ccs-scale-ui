FROM php:7.4.10-apache

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

# Remove server version from 'Server' header and footer signature
RUN sed -i -e 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
RUN sed -i -e 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf

RUN a2enmod rewrite
RUN a2enmod php7

RUN echo "ServerName localhost:$PORT" >> /etc/apache2/apache2.conf

#RUN docker-php-ext-disable sqlite

#COPY ./package.json ./

#COPY ./composer.json ./

# TODO: Recommended but causes OOM error when loading composer during image build
# Configure PHP (and use recommended production settings - see https://hub.docker.com/_/php)
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/conf.d/php.ini"
# RUN sed -i -e 's/expose_php = On/expose_php = Off/' "$PHP_INI_DIR/conf.d/php.ini"

# Workaround:
RUN echo 'expose_php = Off' >> conf.d/security.ini

# increase memory limit to 2.5GB
RUN echo 'memory_limit = 2560M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'opcache.preload=/var/www/html/ccs/var/cache/prod/srcApp_KernelProdContainer.preload.php' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'opcache.memory_consumption=256' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'opcache.max_accelerated_files=20000' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

#copy project into container
COPY ./ ./


# run composer update
RUN composer update

#RUN composer dump-env dev

#install javascript modules
RUN npm install

#COPY ./custom.ini /usr/local/etc/php/conf.d

# restart apache
RUN service apache2 restart

VOLUME /var/www/html/ccs

#set container port
EXPOSE $PORT

CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground ; npm run build


# usage :
# docker build .
# docker run  -d  -p  9030:9030 -e PORT=9030 #id_container
