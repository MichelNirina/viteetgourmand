FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

RUN chmod -R 755 /var/www/html \
    && mkdir -p frontend/assets/images/plats \
    && chmod -R 775 frontend/assets/images/plats

RUN printf '<Directory /var/www/html>\n    AllowOverride All\n    Require all granted\n    Options -Indexes\n</Directory>\n' \
    > /etc/apache2/conf-available/app.conf \
    && a2enconf app

EXPOSE 80
