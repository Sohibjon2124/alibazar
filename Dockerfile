FROM php:8.2-apache

# PHP ва система кенгайтмалари
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev libxml2-dev \
 && docker-php-ext-install \
    zip pdo pdo_mysql mbstring

# mod_rewrite ни ёқамиз
RUN a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Код учун иш жойи
WORKDIR /var/www/html

# Laravel кодини контейнерга кўчириш
COPY . .

# Composer орқали пакетлар
RUN composer install --no-interaction --optimize-autoloader

# Ҳуқуқлар
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🔥 Muhim — public/ ни DocumentRoot сифатида қўйамиз
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Laravel конфигурациясини ёзамиз
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
 && a2enconf laravel

EXPOSE 80

CMD ["apache2-foreground"]
