<?php
// Конфигурация файлового менеджера
// Version: 1.0.6

// Включить/выключить авторизацию
// true - требуется авторизация, false - открытый доступ
define('AUTH_ENABLED', false);

// Учетные данные пользователей (если AUTH_ENABLED = true)
// Формат: 'username' => 'password_hash'
// Для генерации хеша используйте: password_hash('your_password', PASSWORD_DEFAULT)
$users = [
    'admin' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password: password
    // Добавьте своих пользователей здесь
    // 'user1' => password_hash('mypassword', PASSWORD_DEFAULT),
];

// Время жизни сессии в секундах (по умолчанию 24 часа)
define('SESSION_LIFETIME', 86400);

// Для Synology NAS используйте: '/volume1/web/upload'
// Для локальной разработки используйте: __DIR__ . '/upload'
define('BASE_PATH', __DIR__ . '/upload');

// Путь для миниатюр
define('THUMBS_PATH', __DIR__ . '/upload/.thumbs');

// Basis-URL für direkte Dateizugriffe (für Media-Player wie VLC, Dune HD)
// Beispiel: http://192.168.1.100/upload
// Oder: http://nas.local/Dateimanager/upload
// Leer lassen für automatische Erkennung aus window.location im Frontend
define('BASE_URL', '');
