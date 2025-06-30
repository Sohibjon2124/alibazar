# Aishop (Laravel Octane + Docker + RoadRunner)

## Запуск проекта

1. Установите Docker и Docker Compose
2. В корне проекта выполните:

```bash
composer install
cp .env.example .env
php artisan key:generate
docker-compose up --build
