FROM php:7.2.10-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y zlib1g-dev && docker-php-ext-install zip && docker-php-ext-install bcmath && docker-php-ext-install mysqli && docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get install --yes curl wget
RUN apt-get install --yes gnupg2
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash
RUN apt-get install --yes nodejs
RUN apt-get install npm
RUN npm install -g npm@latest
RUN wget https://getcomposer.org/installer
RUN php installer --install-dir /usr/bin/
RUN apt-get install --yes git
RUN rm -rf /var/www/html/*
RUN git clone https://github.com/idekel/url-hortener-backend.git /var/www/html/.

RUN cd /var/www/html/ && composer.phar install
RUN rm -rf /var/www/url-shorterner-frontend
RUN git clone https://github.com/idekel/url-shorterner-frontend.git /var/www/url-shorterner-frontend
RUN cd /var/www/url-shorterner-frontend && npm install
RUN cd /var/www/url-shorterner-frontend && npm run build

RUN cp /var/www/url-shorterner-frontend/build/index.html /var/www/html/resources/views/welcome.blade.php
RUN cp -r /var/www/url-shorterner-frontend/build/static /var/www/html/public/
RUN cp /var/www/html/.env.example /var/www/html/.env
RUN chmod -R 755 /var/www/html && chmod -R o+w /var/www/html/storage
RUN php /var/www/html/modify_vhost.php
RUN cd /var/www/html/ && php artisan key:generate


EXPOSE 80
