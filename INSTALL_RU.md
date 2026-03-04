# File Manager для Synology NAS

Веб-файловый менеджер для работы с папкой `/upload` на Synology Web Station.

## Установка на Synology NAS

1. Скопируйте все файлы из папки `Dateimanager` в `/volume1/web/`
2. Откройте файл `api.php` и измените строку:
   ```php
   define('BASE_PATH', __DIR__ . '/upload');
   ```
   на:
   ```php
   define('BASE_PATH', '/volume1/web/upload');
   ```
3. Убедитесь, что папка `/volume1/web/upload` имеет права на запись
4. Откройте браузер и перейдите на `http://your-nas-ip/`

## Структура файлов

- `index.php` - главная страница
- `api.php` - backend API
- `script.js` - frontend логика
- `style.css` - стили
- `.htaccess` - конфигурация Apache

## Функции

- Создание/удаление/переименование файлов и папок
- Загрузка файлов (drag & drop и кнопка)
- Скачивание файлов
- Перемещение файлов между папками (drag & drop)
- Два режима отображения: сетка и список
- Многоязычность: DE, EN, RU
- Адаптивный дизайн

## Безопасность

- Все операции ограничены папкой `/upload`
- Защита от directory traversal атак
- Валидация путей через `realpath()`

## Требования

- PHP 7.0+
- Apache с mod_rewrite
- Права на запись в `/volume1/web/upload`
