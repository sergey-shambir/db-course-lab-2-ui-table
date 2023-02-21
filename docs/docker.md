# TODO: описать установку в Docker

## Использование

```bash
# Собрать образ (выполняется один раз)
docker-compose build

# Запустить контейнеры в фоновом режиме
docker-compose up -d

# Проверить состояние контейнеров
docker-compose ps

# Смотреть логи контейнеров (Ctrl+C для остановки)
docker-compose logs -f

# Открыть сессию bash в контейнере
docker/bin/ui-table-app-bash

# Чистка кеша Twig
bin/cleanup-cache

# Остановить контейнеры
docker-compose down --remove-orphans
```

Чистка данных:

```bash
# УДАЛИТЬ ВСЕ ДАННЫЕ локальной базы данных (находятся в docker volume)
docker volume rm 2_ui_table_ui_table_db_data
```

## Использование XDebug

Для отладки используйте XDebug:
1. Docker-контейнеры настраивать не надо, в них xdebug уже включён и настроен на подключение к локальному хосту
2. Для браузера установите расширение XDebug Helper
   - Chrome: https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc
   - Firefox: https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/
3. В своей IDE настройте работу с XDebug, используя любую инструкцию для docker-контейнеров
   - Для PhpStorm раздел "PHP > Servers" уже настроен как полагается
