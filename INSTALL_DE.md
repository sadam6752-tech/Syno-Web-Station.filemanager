# File Manager für Synology NAS

Web-Dateimanager für die Arbeit mit dem `/upload`-Ordner auf Synology Web Station.

## Installation auf Synology NAS

1. Kopieren Sie alle Dateien aus dem Ordner `Dateimanager` nach `/volume1/web/`
2. Öffnen Sie die Datei `api.php` und ändern Sie die Zeile:
   ```php
   define('BASE_PATH', __DIR__ . '/upload');
   ```
   zu:
   ```php
   define('BASE_PATH', '/volume1/web/upload');
   ```
3. Stellen Sie sicher, dass der Ordner `/volume1/web/upload` Schreibrechte hat
4. Öffnen Sie den Browser und navigieren Sie zu `http://ihre-nas-ip/`

## Dateistruktur

- `index.php` - Hauptseite
- `api.php` - Backend-API
- `script.js` - Frontend-Logik
- `style.css` - Stile
- `.htaccess` - Apache-Konfiguration

## Funktionen

- Erstellen/Löschen/Umbenennen von Dateien und Ordnern
- Datei-Upload (Drag & Drop und Button)
- Dateien herunterladen
- Dateien zwischen Ordnern verschieben (Drag & Drop)
- Zwei Anzeigemodi: Raster und Liste
- Mehrsprachigkeit: DE, EN, RU
- Responsives Design

## Sicherheit

- Alle Operationen sind auf den `/upload`-Ordner beschränkt
- Schutz vor Directory Traversal-Angriffen
- Pfadvalidierung über `realpath()`

## Anforderungen

- PHP 7.0+
- Apache mit mod_rewrite
- Schreibrechte für `/volume1/web/upload`
