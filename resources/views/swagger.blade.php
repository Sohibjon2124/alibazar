<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swagger UI with Persistent Token</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css">
    <style>
        html, body { margin: 0; padding: 0; height: 100%; }
        #swagger-ui { height: 100vh; }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
    <script>
        window.onload = function () {
            const TOKEN_KEY = 'swagger_auth_token';

            const ui = SwaggerUIBundle({
                url: "{{asset('swagger.yml')}}",  // путь к swagger.yml
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                ],
                layout: "BaseLayout",
                requestInterceptor: (req) => {
                    // Читаем токен из localStorage при каждом запросе
                    const token = localStorage.getItem(TOKEN_KEY);
                    if (token) {
                        req.headers['Authorization'] = 'Bearer ' + token;
                    }
                    return req;
                }
            });

            // Переопределяем authorize, чтобы сохранять токен в localStorage
            const originalAuthorize = ui.authActions.authorize;
            ui.authActions.authorize = function (auth) {
                if (auth && auth.BearerAuth && auth.BearerAuth.value) {
                    localStorage.setItem(TOKEN_KEY, auth.BearerAuth.value);
                }
                return originalAuthorize.call(this, auth);
            };

            // Кнопка очистки токена
            window.clearSwaggerToken = function() {
                localStorage.removeItem(TOKEN_KEY);
                alert('Token cleared. Перезагрузите страницу.');
            }
        };
    </script>

    <button onclick="clearSwaggerToken()" style="position:fixed;bottom:10px;right:10px;z-index:999;">
        Clear Token
    </button>
</body>
</html>
