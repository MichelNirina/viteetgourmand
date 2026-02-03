FROM php:8.2-apache

# Installer les extensions PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Copier ton code
COPY . /var/www/html/

# Exposer le port 80
EXPOSE 80
