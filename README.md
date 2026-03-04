# Syno-Web-Station.filemanager

**Version:** v1.0.0

Web application for file management on Synology NAS via Web Station. / Webanwendung zur Dateiverwaltung auf Synology NAS über Web Station.

![File Manager Screenshot](screenshot.jpg)

# File Manager for Synology NAS WEB-Station

## 🚀 Key Features

### File Management
- ✅ Create, delete, and rename files and folders
- ✅ Copy files and folders with automatic renaming on conflicts
- ✅ Multiple selection via checkboxes
- ✅ File upload (drag & drop and via button)
- ✅ Download single file or multiple files as ZIP archive
- ✅ Upload progress bar with percentage and filename display

### File Movement (Drag & Drop)
- ✅ Drag to folder in list
- ✅ Drag to navigation element (breadcrumb)
- ✅ Drag to empty space to move to parent folder
- ✅ "Move Up" button for mobile devices
- ✅ Visual highlighting of target elements

### Search and Filtering
- ✅ Real-time search by filename
- ✅ Highlighting of found files
- ✅ Hiding of non-matching items
- ✅ Clear search via button or ESC key

### Information and Statistics
- ✅ Detailed file information: size, dates, permissions, MIME type
- ✅ For images: resolution and EXIF data (camera, ISO, exposure)
- ✅ Selected files statistics: count and total size
- ✅ Folder statistics: recursive count of files, folders, and size

### Visualization
- ✅ Two display modes: grid and list
- ✅ Thumbnails for images (requires PHP GD)
- ✅ Dark theme with saved preference
- ✅ Responsive design for mobile devices
- ✅ Font Awesome icons for different file types

### Multilingual Support
- ✅ German (DE)
- ✅ English (EN)
- ✅ Russian (RU)
- ✅ Saved language preference

## 🔒 Security

- Works only within `/upload` folder
- Protection against directory traversal attacks
- All paths validated via `realpath()`
- Access outside BASE_PATH forbidden

## 💻 Technical Requirements

- Synology NAS with Web Station
- Apache + PHP
- PHP GD extension (optional, for thumbnails)
- PHP ZipArchive extension (for downloading multiple files)

## 📱 Responsive Design

- Desktop: Full interface with text on buttons
- Tablet/Phone: Compact interface with icons only
- Toolbar in 3 rows: breadcrumb, statistics/search, buttons
- Optimized for touchscreens

## 🎨 User Interface

- Modern, clean design
- Smooth animations and transitions
- Visual feedback for all actions
- Tooltips for all buttons
- Statistics panel below navigation
