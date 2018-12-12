FROM richarvey/nginx-php-fpm
RUN rm /var/www/html/index.php
RUN mkdir /var/www/php
RUN mkdir /var/www/vendor
COPY php /var/www/php
COPY vendor /var/www/vendor
COPY public_html /var/www/html
EXPOSE 80