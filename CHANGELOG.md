# Changelog

All notable changes to this project will be documented in this file.

## [v1.0.6] - 2026-03-25

### Fixed
- **Double slash in URL (persistent)** - Complete fix for `//` in direct URLs
- Added `ltrim()` to remove leading slashes from file path before appending to base URL
- Improved `getBaseUrl()` to handle edge cases (root directory, manual BASE_URL with trailing slash)
- URL format now always correct: `http://192.168.1.15/upload/file.ts`

---

## [v1.0.5] - 2026-03-25

### Fixed
- **Double slash in URL** - Fixed `//` after IP address in direct URL (e.g., `http://192.168.1.15//upload/...`)
- `getBaseUrl()` now returns URL without trailing slash
- Proper URL format: `http://192.168.1.15/upload/file.ts`

---

## [v1.0.4] - 2026-03-25

### Fixed
- **Long filenames overflow** - Fixed text wrapping in modal for long filenames and URLs
- Added word-break and overflow-wrap for modal title, file info, and URL input
- Modal now scrolls if content exceeds viewport height (max-height: 80vh)

### Changed
- Updated version to 1.0.4 for cache busting

---

## [v1.0.3] - 2026-03-25

### Added
- **Stream-URL feature** for media files - Copy direct URL to clipboard for use in media players (VLC, Dune HD, etc.)
- URL display in file info modal with copy button
- "Open in Player" button to open URL in new tab
- Toast notifications for user feedback
- Automatic BASE_URL detection from server variables
- Manual BASE_URL configuration option in config.php

### Changed
- Updated version to 1.0.3 for cache busting
- Improved file info modal layout with URL section

### Technical
- New `getBaseUrl()` function in api.php for automatic URL detection
- New `copyDirectUrl()` and `openInPlayer()` JavaScript functions
- New `showToast()` notification system
- Added `escapeHtml()` helper function for XSS protection
- CSS styles for URL section and toast notifications
- Translations added for DE, EN, RU languages

---

## [v1.0.2] - 2025-03-06

### Added
- Mobile-friendly "Move to..." functionality for moving files between folders in the same directory
- Visual feedback with highlighted folders and hint bar in move mode
- Ability to cancel move operation
- Support for all mobile browsers (Safari, Firefox, Chrome on iOS/Android)

### Fixed
- Safari iOS caching issues with JavaScript updates
- Button activation on mobile devices
- File selection preservation during move mode

### Improved
- Error handling with try-catch blocks for better stability
- Code safety for cross-browser compatibility
- User experience on touch devices

---

## [v1.0.1] - 2025-03-05

### Added
- Optional authentication system with bcrypt password hashing
- Session-based login with configurable timeout
- Password generation utility (web interface and CLI)
- User management in config.php
- Logout functionality
- Authentication documentation (AUTH_SETUP.md)

### Features
- File and folder management (create, delete, rename, move, copy)
- Drag & drop file upload with progress tracking
- Multiple file selection with checkboxes
- Image thumbnails (200x200px, cached in .thumbs folder)
- Real-time file search with highlighting
- Two view modes (grid and list)
- Dark/light theme toggle
- Multilingual support (German, English, Russian)
- Breadcrumb navigation with drag & drop support
- File information modal (size, type, permissions, EXIF data)
- Folder statistics (recursive file/folder count and total size)
- Selected files statistics (count and total size)
- Mobile-responsive design
- Three ways to move files:
  - Drag to folder
  - Drag to breadcrumb
  - Drag to empty space (moves to parent)
- "Move Up" button for moving selected files to parent folder

### Technical
- PHP backend with JSON API
- Vanilla JavaScript (no frameworks)
- Font Awesome icons
- GD library for thumbnail generation
- Works on Synology Web Station (Apache + PHP)
- Configurable base path for local/NAS deployment

---

## [v1.0.0] - 2025-03-04

### Initial Release
- Basic file manager functionality
- Upload, download, delete operations
- Folder navigation
- Responsive design
