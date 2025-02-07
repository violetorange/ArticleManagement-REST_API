<p align="center">
 <img width="820px" src="https://drive.google.com/uc?export=view&id=1Zl1CFGzInohz8JDtLGAEWBX6s1oA-kod" alt="manager"/>
</p>

# ArticleManagement
ArticleManagement - это веб-приложение для управления публикацией статей (включая все CRUD операции). Проект написан на php 8.3.0, фреймворк symfony 7.2.2. За фронт отвечает react и React-Admin для админ-панели.
В проекте оставлена возможность авторизации через сессию - для управления через admin-панель, а также через token - для использования api, например, через postman.

После разворачивания приложения будет создан тестовый пользователь с правами администратора:<br>
**Логин:** test@test.com<br>
**Пароль:** test

Страница авторизации находится по адресу - **/login**. После отправки формы будет доступна админка по адресу **/admin** (кнопка для перехода будет выведена на экран).

## Установка:
1. Клонирование и окружение:
```
git clone https://github.com/violetorange/ArticleManagement-REST_API.git
composer install
npm install
npm run build
symfony serve
```
2. БД:
```
docker-compose up -d
symfony concole doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

## Админ-панель:

<p align="center">
 <img width="820px" src="https://drive.google.com/uc?export=view&id=1DmP8kaZx5xH_JFAdZKmyMBCYAsaBkoGl" alt="db"/>
</p>

## Пример запроса через токен:

<p align="center">
 <img width="820px" src="https://drive.google.com/uc?export=view&id=1nucnG--BepSJmXQIohwjkUAxgviDzkCY" alt="db"/>
</p>
