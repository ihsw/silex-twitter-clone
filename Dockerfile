FROM ubuntu:14.04

RUN apt-get update && apt-get install -yq curl php5-cli php5-fpm nginx supervisor

EXPOSE 80

# misc
ENV INSTALL_DIR /srv/silex-twitter-clone
ENV APP_DIR ./app
RUN mkdir $INSTALL_DIR

# installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# installing app dependencies
COPY $APP_DIR/composer.json $INSTALL_DIR/composer.json
RUN cd $INSTALL_DIR && composer install

# installing the app assets
COPY $APP_DIR/web $INSTALL_DIR/web
COPY $APP_DIR/src $INSTALL_DIR/src
COPY $APP_DIR/templates $INSTALL_DIR/templates
RUN mkdir $INSTALL_DIR/cache
RUN chown www-data:www-data $INSTALL_DIR/cache
RUN chmod 744 $INSTALL_DIR/cache

# nginx setup
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf
COPY ./supervisor/nginx.conf /etc/supervisor/conf.d/nginx.conf

# php-fpm setup
COPY ./supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf

# installing the nginx site
ENV SITE_DEST /etc/nginx/sites-available/silex-twitter-clone
COPY ./nginx/silex-twitter-clone $SITE_DEST
RUN ln -s $SITE_DEST /etc/nginx/sites-enabled/silex-twitter-clone
RUN rm /etc/nginx/sites-enabled/default

CMD ["supervisord", "-n"]