# File Manager for Synology NAS

**Version:** v1.0.1

Web file manager for working with the `/upload` folder on Synology Web Station.

## Installation on Synology NAS

1. Copy all files from the `Dateimanager` folder to `/volume1/web/`
2. Open the `api.php` file and change the line:
   ```php
   define('BASE_PATH', __DIR__ . '/upload');
   ```
   to:
   ```php
   define('BASE_PATH', '/volume1/web/upload');
   ```
3. Ensure that the `/volume1/web/upload` folder has write permissions
4. Open your browser and navigate to `http://your-nas-ip/`

## Local Testing

1. Place the files in the web server folder (e.g., `htdocs/Dateimanager/`)
2. Keep the line in `api.php`:
   ```php
   define('BASE_PATH', __DIR__ . '/upload');
   ```
3. The `upload` folder will be created automatically in the same directory
4. Open `http://localhost/Dateimanager/`

## File Structure

- `index.php` - main page
- `api.php` - backend API
- `script.js` - frontend logic
- `style.css` - styles
- `.htaccess` - Apache configuration

## Features

- Create/delete/rename files and folders
- File upload (drag & drop and button)
- Download files
- Move files between folders (drag & drop)
- Two display modes: grid and list
- Multilingual: DE, EN, RU
- Responsive design
- **Optional authentication** (see AUTH_SETUP.md)

## Security

- All operations are restricted to the `/upload` folder
- Protection against directory traversal attacks
- Path validation via `realpath()`
- Optional authentication system with password hashing

## Setup Authentication

For enabling authentication see detailed instructions in `AUTH_SETUP.md`

Quick start:
1. Open `config.php`
2. Set `AUTH_ENABLED = true`
3. Generate password hash via `generate_password.php`
4. Add users to the `$users` array
5. Delete `generate_password.php`

### Example: Adding a user

```bash
# 1. Open in browser
http://your-nas-ip/generate_password.php

# 2. Enter password: MyPassword123
# 3. Copy the generated hash

# 4. Add to config.php:
$users = [
    'admin' => '$2y$10$...',  // password (default)
    'john' => '$2y$10$...',   // MyPassword123 (your hash)
];

# 5. Delete generate_password.php
# 6. Login as john with password MyPassword123
```

## Requirements

- PHP 7.0+
- Apache with mod_rewrite
- Write permissions for `/volume1/web/upload`

## Testing

After installation, check:
1. Creating folders and files
2. Uploading files
3. Moving files via drag & drop
4. Language switching
5. Display mode switching
