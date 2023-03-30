FROM php:5.3-apache
RUN a2enmod vhost_alias
RUN a2enmod rewrite
RUN echo "Copio php.ini"

COPY php.ini /usr/local/etc/php/php.ini
COPY apache2.conf /etc/apache2/apache2.conf


RUN service apache2 restart