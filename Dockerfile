FROM richarvey/nginx-php-fpm

WORKDIR /var/www/html

ADD composer.json /var/www/html
ADD . /var/www/html
ADD ./docker/default.conf /etc/nginx/sites-available/default.conf

RUN php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php \
    && php composer-setup.php --filename=composer \
    && ./composer install -v --prefer-dist -o