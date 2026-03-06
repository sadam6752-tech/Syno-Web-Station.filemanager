# Настройка авторизации / Authentication Setup

**Версия / Version:** v1.0.2

## Русский (Russian)

### Включение авторизации

1. Откройте файл `config.php`
2. Измените `AUTH_ENABLED` на `true`:
   ```php
   define('AUTH_ENABLED', true);
   ```

### Создание пользователей

#### Способ 1: Через веб-интерфейс

1. Откройте в браузере: `http://your-nas-ip/generate_password.php`
2. Введите желаемый пароль
3. Скопируйте сгенерированный хеш
4. Добавьте в `config.php` в массив `$users`:
   ```php
   $users = [
       'admin' => '$2y$10$...',  // ваш хеш
       'user1' => '$2y$10$...',  // другой пользователь
   ];
   ```
5. **ВАЖНО:** Удалите файл `generate_password.php` после использования!

#### Способ 2: Через командную строку

```bash
php generate_password.php
```

### Настройка времени сессии

В `config.php` измените `SESSION_LIFETIME` (в секундах):
```php
define('SESSION_LIFETIME', 86400); // 24 часа
```

### Отключение авторизации

В `config.php` установите:
```php
define('AUTH_ENABLED', false);
```

---

## Deutsch (German)

### Authentifizierung aktivieren

1. Öffnen Sie die Datei `config.php`
2. Ändern Sie `AUTH_ENABLED` auf `true`:
   ```php
   define('AUTH_ENABLED', true);
   ```

### Benutzer erstellen

#### Methode 1: Über Web-Interface

1. Öffnen Sie im Browser: `http://ihre-nas-ip/generate_password.php`
2. Geben Sie das gewünschte Passwort ein
3. Kopieren Sie den generierten Hash
4. Fügen Sie in `config.php` zum `$users` Array hinzu:
   ```php
   $users = [
       'admin' => '$2y$10$...',  // Ihr Hash
       'user1' => '$2y$10$...',  // anderer Benutzer
   ];
   ```
5. **WICHTIG:** Löschen Sie die Datei `generate_password.php` nach der Verwendung!

#### Methode 2: Über Kommandozeile

```bash
php generate_password.php
```

### Session-Dauer konfigurieren

In `config.php` ändern Sie `SESSION_LIFETIME` (in Sekunden):
```php
define('SESSION_LIFETIME', 86400); // 24 Stunden
```

### Authentifizierung deaktivieren

In `config.php` setzen Sie:
```php
define('AUTH_ENABLED', false);
```

---

## English

### Enable Authentication

1. Open the file `config.php`
2. Change `AUTH_ENABLED` to `true`:
   ```php
   define('AUTH_ENABLED', true);
   ```

### Create Users

#### Method 1: Via Web Interface

1. Open in browser: `http://your-nas-ip/generate_password.php`
2. Enter desired password
3. Copy the generated hash
4. Add to `config.php` in the `$users` array:
   ```php
   $users = [
       'admin' => '$2y$10$...',  // your hash
       'user1' => '$2y$10$...',  // another user
   ];
   ```
5. **IMPORTANT:** Delete the `generate_password.php` file after use!

#### Method 2: Via Command Line

```bash
php generate_password.php
```

### Configure Session Lifetime

In `config.php` change `SESSION_LIFETIME` (in seconds):
```php
define('SESSION_LIFETIME', 86400); // 24 hours
```

### Disable Authentication

In `config.php` set:
```php
define('AUTH_ENABLED', false);
```

---

## Безопасность / Sicherheit / Security

- ✅ Пароли хешируются с помощью `password_hash()` (bcrypt)
- ✅ Защита от брутфорса через HTTP 401
- ✅ Автоматический выход по истечении времени сессии
- ✅ Безопасное хранение сессий на сервере
- ⚠️ Используйте HTTPS для защиты передачи данных
- ⚠️ Удалите `generate_password.php` после настройки
- ⚠️ Используйте сложные пароли

## Пример использования / Verwendungsbeispiel / Usage Example

### Сценарий: Добавление нового пользователя

**Шаг 1:** Откройте `generate_password.php` в браузере
```
http://192.168.1.15/generate_password.php
```

**Шаг 2:** Введите данные нового пользователя
- Username: `john`
- Password: `SecurePass2024!`

**Шаг 3:** Нажмите "Generate Hash" и получите:
```
Generated Hash:
$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP

Add to config.php:
'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',
```

**Шаг 4:** Откройте `config.php` и добавьте пользователя:
```php
$users = [
    'admin' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',  // SecurePass2024!
];
```

**Шаг 5:** Сохраните `config.php`

**Шаг 6:** Удалите `generate_password.php`:
```bash
rm /volume1/web/generate_password.php
```

**Шаг 7:** Войдите в систему:
- Откройте `http://192.168.1.15/`
- Username: `john`
- Password: `SecurePass2024!`

✅ Готово! Пользователь `john` может работать с файловым менеджером.

---

### Scenario: Adding a new user

**Step 1:** Open `generate_password.php` in browser
```
http://192.168.1.15/generate_password.php
```

**Step 2:** Enter new user credentials
- Username: `john`
- Password: `SecurePass2024!`

**Step 3:** Click "Generate Hash" and get:
```
Generated Hash:
$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP

Add to config.php:
'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',
```

**Step 4:** Open `config.php` and add user:
```php
$users = [
    'admin' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',  // SecurePass2024!
];
```

**Step 5:** Save `config.php`

**Step 6:** Delete `generate_password.php`:
```bash
rm /volume1/web/generate_password.php
```

**Step 7:** Login to system:
- Open `http://192.168.1.15/`
- Username: `john`
- Password: `SecurePass2024!`

✅ Done! User `john` can now use the file manager.

---

### Szenario: Neuen Benutzer hinzufügen

**Schritt 1:** Öffnen Sie `generate_password.php` im Browser
```
http://192.168.1.15/generate_password.php
```

**Schritt 2:** Geben Sie neue Benutzerdaten ein
- Benutzername: `john`
- Passwort: `SecurePass2024!`

**Schritt 3:** Klicken Sie auf "Generate Hash" und erhalten Sie:
```
Generated Hash:
$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP

Add to config.php:
'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',
```

**Schritt 4:** Öffnen Sie `config.php` und fügen Sie Benutzer hinzu:
```php
$users = [
    'admin' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    'john' => '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOP',  // SecurePass2024!
];
```

**Schritt 5:** Speichern Sie `config.php`

**Schritt 6:** Löschen Sie `generate_password.php`:
```bash
rm /volume1/web/generate_password.php
```

**Schritt 7:** Melden Sie sich im System an:
- Öffnen Sie `http://192.168.1.15/`
- Benutzername: `john`
- Passwort: `SecurePass2024!`

✅ Fertig! Benutzer `john` kann jetzt den Dateimanager verwenden.

---

## Стандартные учетные данные / Standard-Anmeldedaten / Default Credentials

**Username:** admin  
**Password:** password

⚠️ **Измените пароль сразу после первого входа!**  
⚠️ **Ändern Sie das Passwort sofort nach der ersten Anmeldung!**  
⚠️ **Change the password immediately after first login!**
