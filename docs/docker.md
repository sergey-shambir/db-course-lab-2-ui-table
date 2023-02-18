# TODO: описать установку в Docker

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

# Остановить контейнеры
docker-compose down --remove-orphans
```
