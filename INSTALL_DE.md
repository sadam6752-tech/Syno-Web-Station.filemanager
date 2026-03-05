# File Manager für Synology NAS

**Version:** v1.0.1

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

## Lokales Testen

1. Platzieren Sie die Dateien im Webserver-Ordner (z.B. `htdocs/Dateimanager/`)
2. Belassen Sie in `api.php` die Zeile:
   ```php
   define('BASE_PATH', __DIR__ . '/upload');
   ```
3. Der Ordner `upload` wird automatisch im selben Verzeichnis erstellt
4. Öffnen Sie `http://localhost/Dateimanager/`

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
- **Optionale Authentifizierung** (siehe AUTH_SETUP.md)

## Sicherheit

- Alle Operationen sind auf den `/upload`-Ordner beschränkt
- Schutz vor Directory Traversal-Angriffen
- Pfadvalidierung über `realpath()`
- Optionales Authentifizierungssystem mit Passwort-Hashing

## Authentifizierung einrichten

Für die Aktivierung der Authentifizierung siehe detaillierte Anleitung in `AUTH_SETUP.md`

Schnellstart:
1. Öffnen Sie `config.php`
2. Setzen Sie `AUTH_ENABLED = true`
3. Generieren Sie Passwort-Hash über `generate_password.php`
4. Fügen Sie Benutzer zum `$users` Array hinzu
5. Löschen Sie `generate_password.php`

### Beispiel: Benutzer hinzufügen

```bash
# 1. Öffnen Sie im Browser
http://ihre-nas-ip/generate_password.php

# 2. Geben Sie Passwort ein: MeinPasswort123
# 3. Kopieren Sie den generierten Hash

# 4. Fügen Sie in config.php hinzu:
$users = [
    'admin' => '$2y$10$...',  // password (Standard)
    'john' => '$2y$10$...',   // MeinPasswort123 (Ihr Hash)
];

# 5. Löschen Sie generate_password.php
# 6. Melden Sie sich als john mit Passwort MeinPasswort123 an
```

## Anforderungen

- PHP 7.0+
- Apache mit mod_rewrite
- Schreibrechte für `/volume1/web/upload`

## Testen

Nach der Installation überprüfen Sie:
1. Erstellen von Ordnern und Dateien
2. Hochladen von Dateien
3. Verschieben von Dateien per Drag & Drop
4. Sprachwechsel
5. Wechsel der Anzeigemodi
