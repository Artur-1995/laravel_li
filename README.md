<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

## Реализация методов REST API для работы с пользователями

Создание пользователя:
- Cсылка: */api/user.
- Метод POST.
- Структура запроса:

    {

    "name": "***name***",

    "email": "***email***",

    "password": "***password***",

    "password_confirmation": "***password_confirmation***"

    }

 Получить информацию о пользователе:

- Cсылка: */api/user/{id}.
- Метод GET.
- Структура запроса:

    {

    "api_token": "***you_api_token***"

    }

Обновление информации пользователя:

- Cсылка: */api/user/{id}.
- Метод PUT/PATCH.
- Структура запроса:

    {

    "api_token": "***you_api_token***"

    }

Удаление пользователя:

- Cсылка: */api/user/{id}.
- Метод DELETE.
- Структура запроса:

    {

    "api_token": "***you_api_token***"

    }
