# Пример: отображение списка ограничений на ввоз С/Х товаров в Россию

Пример основан на PHP8 и MySQL8, проверен на Linux.

Данные взяты из портала открытых данных: https://data.gov.ru/opendata/7708523530-ogranichennavvoz

## Скриншот

![](docs/ui-table-screenshot.png)

## Запуск примера на Linux

Краткий план действий:

1. Установить docker и docker-compose
2. Запустить контейнеры по инструкции из файла `docs/docker.md`
3. Открыть в MySQL Workbench и выполнить последовательно SQL запросы из двух файлов:
   - `data/create_user.sql`
   - `data/init.sql`
4. Открыть в браузере http://localhost

В идеале у вас:
- По адресу http://localhost открывается таблица со списокм ограничений
- При включении XDebug отладка работает

## Запуск примера в Windows

Краткий план действий:

1. Установить MySQL 8 и PHP 8.2
2. Запустить MySQL server
3. Открыть в MySQL Workbench и выполнить последовательно SQL запросы из двух файлов:
   - `data/create_user.sql`
   - `data/init.sql`
4. Открыть консоль и запустить отладочный сервер PHP:
    - `cd public`
    - `php -S localhost:8888`
5. Открыть в браузере `http://localhost:8888`
