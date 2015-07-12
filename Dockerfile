FROM ubuntu:14.04

RUN apt-get update && apt-get install -yq curl php5-cli php5-fpm nginx

EXPOSE 80

# misc
ENV INSTALL_DIR /srv/silex-twitter-clone
ENV APP_DIR ./app

# installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# installing the app
RUN mkdir $INSTALL_DIR
COPY $APP_DIR/composer.json $INSTALL_DIR/composer.json
COPY $APP_DIR/src $INSTALL_DIR/src
RUN cd $INSTALL_DIR && composer install

# installing the nginx site
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf
ENV SITE_DEST /etc/nginx/sites-available/silex-twitter-clone
COPY ./nginx/silex-twitter-clone $SITE_DEST
RUN ln -s $SITE_DEST /etc/nginx/sites-enabled/silex-twitter-clone

CMD "nginx"