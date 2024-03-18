Тестовое задание
============

-------------
Требуются установленные пакеты: ``` docker docker-compose ```

Шаги выполнения по ``` docker-compose ```:
1. ``` docker-compose -f ./docker-compose.yml build --build-arg USER_ID=$(id -u ${USER}) --build-arg GROUP_ID=$(id -g ${USER}) issue-app ```
2. ``` docker-compose -f ./docker-compose.yml build --build-arg USER_ID=$(id -u ${USER}) --build-arg GROUP_ID=$(id -g ${USER}) issue-web ```
3. ``` docker-compose up -d ```

Шаги по настройке yii:
1. ``` docker exec -u www-data -it issue-app bash ```
2. ``` composer install ```
3. ``` ./yii migrate ```

После настройки будет доступно по адресу ``` http://130.10.0.2/ ```

На странице авторизации есть кнопка регистрации.

После регистрации/авторизации будет доступен список пользователей.


```
Тестове завдання:

Реалізувати дві сторінки:
    1. Сторінка з формою для створення користувача
    2. Сторінка з таблицею даних для відображення переліку створених
користувачів

Умови завдань:
    Загальні умови:
        - використання фреймворку Yii2
        - використання docker контейнерів:
            issue_web: ВЕБ-сервер
            issue_app: додаток
            issue_db: база даних
        - зберігання створених даних в БД
        - відображення даних з БД
        - створити всі небхідні компоненти, максимально використовуючи
можливості фреймворку
        - запуск проекта за допомогою docker compose
        - організація, підключення та взаємодія компонентів: максимально
використовувати можливості фреймворку
        - НЕ використовувати механізми модулів фреймворку
        - на кожній сторінці маленьке меню з пунктами (сторінками) форми та
таблиці (щоб можна було перемикатись)
    1. Сторінка з формою для створення користувача
        - поля для форми (всі текстові):
            - email
            - phone
            - last_name
            - first_name
            - middle_name
            - document
        - обовʼязкові поля:
            - last_name
            - first_name
            - document
    2. Сторінка з таблицею даних для відображення переліку створених
користувачів
        - використання компонента GridView
        - реалізувати фільтрацію та сортування (функції "з коробки")
```
