<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=1.0.7">
</head>
<body>
    <header>
        <h1 id="header-title">Dateimanager</h1>
        <div class="header-controls">
            <?php if (AUTH_ENABLED): ?>
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span><?php echo htmlspecialchars(getCurrentUser()); ?></span>
                <a href="?action=logout" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
            <?php endif; ?>
            <div class="view-toggle">
                <button id="view-grid" class="active" title="Grid"><i class="fas fa-th"></i></button>
                <button id="view-list" title="List"><i class="fas fa-list"></i></button>
            </div>
            <button id="theme-toggle" class="theme-toggle" title="Theme">
                <i class="fas fa-moon"></i>
            </button>
            <div class="language-selector">
                <button data-lang="de" class="active">DE</button>
                <button data-lang="en">EN</button>
                <button data-lang="ru">RU</button>
            </div>
        </div>
    </header>

    <main>
        <div class="toolbar">
            <div class="breadcrumb" id="breadcrumb"></div>
            <div class="stats-bar" id="stats-bar">
                <span id="selection-stats" class="stats-item" style="display: none;"></span>
                <span id="folder-stats" class="stats-item"></span>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Search files...">
                <button id="search-clear" class="search-clear" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="actions">
                <button id="btn-select-all" title="Alle auswählen / Alle abwählen"><i class="fas fa-check-square"></i> <span id="btn-select-all-text">Alle auswählen</span></button>
                <button id="btn-copy" disabled title="Ausgewählte kopieren"><i class="fas fa-copy"></i> <span id="btn-copy-text">Kopieren</span></button>
                <button id="btn-move-to" disabled title="Ausgewählte in Ordner verschieben"><i class="fas fa-folder-open"></i> <span id="btn-move-to-text">Verschieben nach...</span></button>
                <button id="btn-move-up" disabled title="Ausgewählte in übergeordneten Ordner verschieben"><i class="fas fa-level-up-alt"></i> <span id="btn-move-up-text">Nach oben verschieben</span></button>
                <button id="btn-download-selected" disabled title="Ausgewählte Dateien herunterladen"><i class="fas fa-download"></i> <span id="btn-download-selected-text">Ausgewählte herunterladen</span></button>
                <button id="btn-delete-selected" disabled class="btn-danger" title="Ausgewählte Dateien löschen"><i class="fas fa-trash"></i> <span id="btn-delete-selected-text">Ausgewählte löschen</span></button>
                <button id="btn-new-folder" title="Neuen Ordner erstellen"><i class="fas fa-folder-plus"></i> <span id="btn-new-folder-text">Neuer Ordner</span></button>
                <button id="btn-new-file" title="Neue Datei erstellen"><i class="fas fa-file-circle-plus"></i> <span id="btn-new-file-text">Neue Datei</span></button>
                <button id="btn-upload" title="Dateien hochladen"><i class="fas fa-upload"></i> <span id="btn-upload-text">Hochladen</span></button>
                <input type="file" id="file-input" multiple style="display: none;">
            </div>
        </div>

        <div id="drop-zone" class="drop-zone">
            <i class="fas fa-cloud-upload-alt"></i>
            <p id="drop-zone-text">Dateien hier ablegen</p>
        </div>

        <div id="upload-progress" class="upload-progress">
            <div class="upload-progress-header">
                <span id="upload-progress-text">Uploading files...</span>
                <button id="upload-progress-close" class="upload-progress-close">&times;</button>
            </div>
            <div class="upload-progress-bar">
                <div id="upload-progress-fill" class="upload-progress-fill"></div>
            </div>
            <div id="upload-progress-details" class="upload-progress-details"></div>
        </div>

        <div id="file-list" class="file-list grid"></div>
    </main>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-title"></h2>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="script.js?v=1.0.7"></script>
</body>
</html>
