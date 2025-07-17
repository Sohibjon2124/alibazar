# 🚀 Laravel Octane + RoadRunner + MySQL + phpMyAdmin

Данный проект предназначен для запуска Laravel с сервером Octane на базе RoadRunner в окружении Docker. В качестве базы данных используется MySQL, а для управления — phpMyAdmin.

---

## 📦 Установка и запуск

1. **Соберите и запустите контейнеры:**

   docker-compose up -d --build 

Создайте .env файл:

    docker exec -it laravel-roadrunner cp .env.example .env
    
Установите зависимости:

    docker exec -it laravel-roadrunner composer install

Сгенерируйте ключ приложения:

    docker exec -it laravel-roadrunner php artisan key:generate

Запустите миграции и начальные данные:

    docker exec -it laravel-roadrunner php artisan migrate


Pull қилиш учун:
    git pull
тамом ишлаши керак

🌐 Доступ к сервисам
    Laravel (Swagger):
        👉 http://localhost:8000

    phpMyAdmin:
        👉 http://localhost:8081

Регистация:
    POST http://127.0.0.1:8000/api/v1/auth/register
        {
            "name": "John",
            "tel": "+992929614111",
            "password": "secret123",
            "password_confirmation": "secret123"
        }

Login
    POST http://127.0.0.1:8000/api/v1/auth/login
      {
        "tel": "+992929614112",
        "password": "secret123"
      }

Refresh
    POST http://127.0.0.1:8000/api/auth/refresh
        HEAD
            Authorization: Bearer ACCESS_TOKEN

        BODY
            {
                "refresh_token": "REFRESH_TOKEN"
            }