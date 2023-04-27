FROM php:7.4-apache

RUN a2enmod rewrite

RUN apt-get update \
    && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mysqli zip gd mbstring

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN printf "\
    <VirtualHost *:80>\n\
        ServerName localhost\n\
        DocumentRoot /var/www/html/public\n\
        <Directory /var/www/html/public>\n\
            Options Indexes FollowSymLinks\n\
            AllowOverride All\n\
            Require all granted\n\
        </Directory>\n\
        ErrorLog /var/log/apache2/error.log\n\
        CustomLog /var/log/apache2/access.log combined\n\
    </VirtualHost>\n" > /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["sh", "-c", "composer install --no-interaction --no-plugins --no-scripts && apache2-foreground"]