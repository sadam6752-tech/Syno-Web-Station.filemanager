# File Manager for Synology NAS

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

## Security

- All operations are restricted to the `/upload` folder
- Protection against directory traversal attacks
- Path validation via `realpath()`

## Requirements

- PHP 7.0+
- Apache with mod_rewrite
- Write permissions for `/volume1/web/upload`
