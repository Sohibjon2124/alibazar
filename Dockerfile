FROM php:8.2-cli

# Установка системных зависимостей и расширений
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libonig-dev libxml2-dev \
  && docker-php-ext-install \
    zip pdo pdo_mysql mbstring sockets pcntl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

docker

# Laravel + Octane
RUN composer install --no-interaction --optimize-autoloader \
 && composer require laravel/octane --quiet \
 && php artisan octane:install --server=roadrunner --quiet

EXPOSE 8080

CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8080"]
