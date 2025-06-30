docker-compose up -d --build
docker exec -it laravel-roadrunner cp .env.example .env
docker exec -it laravel-roadrunner composer install
docker exec -it laravel-roadrunner php artisan key:generate