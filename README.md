# REST API для управления задачами (To-Do List)

Простой REST API для управления списком задач, реализованный на Laravel с использованием SQLite базы данных.

## Задание

Разработка простого API для управления задачами.

### Требования к реализации:

1. Создать Laravel-проект
2. Реализовать API с CRUD-операциями для задач:
   - Создание задачи: POST /api/v1/tasks (поля: title, description, status)
   - Просмотр списка задач: GET /api/v1/tasks (возвращает все задачи)
   - Просмотр одной задачи: GET /api/v1/tasks/{id}
   - Обновление задачи: PUT /api/v1/tasks/{id}
   - Удаление задачи: DELETE /api/v1/tasks/{id}
3. Валидация данных (например, title не должен быть пустым)
4. Использовать SQLite в качестве базы данных

## Описание проекта

Проект реализует REST API для управления задачами с использованием следующих технологий:

- **Laravel 12** - PHP фреймворк
- **SQLite** - база данных
- **PHPUnit** - тестирование

### Структура базы данных

Таблица `tasks`:
- `id` (bigint, primary key, auto increment)
- `title` (string, required) - название задачи
- `description` (text, nullable) - описание задачи
- `status` (string, enum) - статус задачи (pending, in_progress, completed)
- `created_at` (timestamp)
- `updated_at` (timestamp)

### Статусы задач

Используется Enum `TaskStatus` со следующими значениями:
- `pending` - ожидает выполнения
- `in_progress` - в процессе выполнения
- `completed` - выполнена

## API Документация

Полное описание API доступно в файле [openapi.yml](openapi.yml) в формате OpenAPI 3.0.

### Доступные endpoints:

- `GET /api/v1/tasks` - получить список всех задач
- `GET /api/v1/tasks/{id}` - получить задачу по ID
- `POST /api/v1/tasks` - создать новую задачу
- `PUT /api/v1/tasks/{id}` - обновить задачу
- `DELETE /api/v1/tasks/{id}` - удалить задачу

### HTTP коды ответов

- `200 OK` - успешный запрос (GET, PUT)
- `201 Created` - ресурс успешно создан (POST)
- `204 No Content` - ресурс успешно удален (DELETE)
- `404 Not Found` - ресурс не найден
- `422 Unprocessable Entity` - ошибка валидации


## Особенности реализации

- Использование **FormRequest** для валидации данных
- **Model Binding** для автоматической загрузки моделей из роутов
- **Enum** для типизации статусов задач
- **RESTful** архитектура с правильными HTTP кодами ответов
- Полное покрытие тестами всех операций

## Тестирование

Проект включает автоматические тесты для всех CRUD операций API.

### Запуск тестов

```bash
php artisan test
```

Или через PHPUnit:

```bash
vendor/bin/phpunit
```

## Технологии

- PHP 8.4
- Laravel 12
- SQLite
- PHPUnit
