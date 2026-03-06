# Changelog

All notable changes to this project will be documented in this file.

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
