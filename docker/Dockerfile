FROM node:alpine AS frontend

RUN apk add git

WORKDIR /app
RUN git clone https://github.com/idekel/url-shorterner-frontend.git

WORKDIR /app/url-shorterner-frontend
COPY node_modules node_modules
RUN npm run build



FROM php:7.3.2-apache-stretch

RUN a2enmod rewrite

#utils
RUN apt-get update
RUN apt-get install --yes curl wget htop
RUN apt-get install --yes libzip-dev

# php exts
RUN apt-get install -y zlib1g-dev && docker-php-ext-install zip && docker-php-ext-install bcmath && docker-php-ext-install mysqli && docker-php-ext-install pdo_mysql


RUN wget https://getcomposer.org/installer
RUN php installer --install-dir /usr/bin/
RUN apt-get install --yes git

RUN rm -rf /var/www/html/*
RUN git clone https://github.com/idekel/url-hortener-backend.git /var/www/html/.
RUN cd /var/www/html/ && composer.phar install

COPY --from=frontend /app/url-shorterner-frontend/build/index.html /var/www/html/resources/views/welcome.blade.php
COPY --from=frontend /app/url-shorterner-frontend/build/static/ /var/www/html/public/static


EXPOSE 80

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN cp /var/www/html/.env.example /var/www/html/.env
RUN chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/storage
RUN cd /var/www/html/ && php artisan key:generate
