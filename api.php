<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// Для Synology NAS используйте: '/volume1/web/upload'
// Для локальной разработки используйте: __DIR__ . '/upload'
define('BASE_PATH', __DIR__ . '/upload');
define('THUMBS_PATH', __DIR__ . '/upload/.thumbs');

// Создать папку upload если не существует
if (!file_exists(BASE_PATH)) {
    @mkdir(BASE_PATH, 0755, true);
}

// Создать папку для миниатюр
if (!file_exists(THUMBS_PATH)) {
    @mkdir(THUMBS_PATH, 0755, true);
}

function validatePath($path) {
    // Если путь пустой, возвращаем BASE_PATH
    if ($path === '' || $path === '.') {
        return realpath(BASE_PATH);
    }
    
    // Убираем начальный слеш если есть
    $path = ltrim($path, '/');
    
    $fullPath = BASE_PATH . '/' . $path;
    $basePath = realpath(BASE_PATH);
    
    // Для несуществующих путей проверяем родительскую директорию
    if (!file_exists($fullPath)) {
        $parentDir = dirname($fullPath);
        $realParent = realpath($parentDir);
        
        if ($realParent === false || strpos($realParent, $basePath) !== 0) {
            return false;
        }
        return $fullPath;
    }
    
    $realPath = realpath($fullPath);
    
    if ($realPath === false || strpos($realPath, $basePath) !== 0) {
        return false;
    }
    
    return $realPath;
}

function getFileIcon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'jpg' => 'fa-file-image', 'jpeg' => 'fa-file-image', 'png' => 'fa-file-image', 'gif' => 'fa-file-image',
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word', 'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel', 'xlsx' => 'fa-file-excel',
        'zip' => 'fa-file-archive', 'rar' => 'fa-file-archive', '7z' => 'fa-file-archive',
        'php' => 'fa-file-code', 'js' => 'fa-file-code', 'html' => 'fa-file-code', 'css' => 'fa-file-code',
        'mp4' => 'fa-file-video', 'avi' => 'fa-file-video', 'mkv' => 'fa-file-video',
        'mp3' => 'fa-file-audio', 'wav' => 'fa-file-audio',
    ];
    return $icons[$ext] ?? 'fa-file';
}

function isImage($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

function createThumbnail($sourcePath, $filename) {
    if (!extension_loaded('gd')) {
        return false;
    }
    
    if (!isImage($filename)) {
        return false;
    }
    
    $thumbPath = THUMBS_PATH . '/' . md5($filename . filemtime($sourcePath)) . '.jpg';
    
    // Если миниатюра уже существует
    if (file_exists($thumbPath)) {
        return $thumbPath;
    }
    
    try {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Создать изображение из источника
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $source = @imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $source = @imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $source = @imagecreatefromgif($sourcePath);
                break;
            case 'webp':
                $source = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        // Получить размеры
        $width = imagesx($source);
        $height = imagesy($source);
        
        // Размер миниатюры
        $thumbWidth = 200;
        $thumbHeight = 200;
        
        // Вычислить пропорции
        $ratio = min($thumbWidth / $width, $thumbHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
        
        // Создать миниатюру
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        // Сохранить прозрачность для PNG
        if ($ext === 'png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }
        
        // Ресайз
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Сохранить
        imagejpeg($thumb, $thumbPath, 85);
        
        // Освободить память
        imagedestroy($source);
        imagedestroy($thumb);
        
        return $thumbPath;
    } catch (Exception $e) {
        return false;
    }
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'test_gd':
            $gdInfo = [
                'gd_loaded' => extension_loaded('gd'),
                'gd_version' => function_exists('gd_info') ? gd_info() : 'N/A',
                'thumbs_path' => THUMBS_PATH,
                'thumbs_exists' => file_exists(THUMBS_PATH),
                'thumbs_writable' => is_writable(THUMBS_PATH)
            ];
            echo json_encode(['success' => true, 'info' => $gdInfo]);
            break;
            
        case 'list':
            $path = $_GET['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !is_dir($fullPath)) {
                throw new Exception('Invalid path');
            }
            
            $items = [];
            $files = scandir($fullPath);
            
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || $file === '.thumbs') continue;
                
                $itemPath = $fullPath . '/' . $file;
                $relativePath = $path ? $path . '/' . $file : $file;
                
                $isImage = is_file($itemPath) && isImage($file);
                $thumbnail = null;
                
                // Создать миниатюру для изображений
                if ($isImage) {
                    $thumbPath = createThumbnail($itemPath, $file);
                    if ($thumbPath) {
                        $thumbnail = 'api.php?action=thumbnail&path=' . urlencode($relativePath);
                    }
                }
                
                $items[] = [
                    'name' => $file,
                    'path' => $relativePath,
                    'type' => is_dir($itemPath) ? 'folder' : 'file',
                    'size' => is_file($itemPath) ? filesize($itemPath) : 0,
                    'modified' => filemtime($itemPath),
                    'icon' => is_dir($itemPath) ? 'fa-folder' : getFileIcon($file),
                    'thumbnail' => $thumbnail,
                    'isImage' => $isImage
                ];
            }
            
            echo json_encode(['success' => true, 'items' => $items]);
            break;

        case 'create_folder':
            $path = $_POST['path'] ?? '';
            $name = $_POST['name'] ?? '';
            
            if (empty($name)) throw new Exception('Name required');
            
            $fullPath = validatePath($path . '/' . $name);
            if (!$fullPath) throw new Exception('Invalid path');
            
            if (file_exists($fullPath)) throw new Exception('Already exists');
            
            mkdir($fullPath, 0755, true);
            echo json_encode(['success' => true]);
            break;
            
        case 'create_file':
            $path = $_POST['path'] ?? '';
            $name = $_POST['name'] ?? '';
            
            if (empty($name)) throw new Exception('Name required');
            
            $fullPath = validatePath($path . '/' . $name);
            if (!$fullPath) throw new Exception('Invalid path');
            
            if (file_exists($fullPath)) throw new Exception('Already exists');
            
            file_put_contents($fullPath, '');
            echo json_encode(['success' => true]);
            break;
            
        case 'delete':
            $path = $_POST['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !file_exists($fullPath)) {
                throw new Exception('Invalid path');
            }
            
            if (is_dir($fullPath)) {
                function deleteDir($dir) {
                    $files = array_diff(scandir($dir), ['.', '..']);
                    foreach ($files as $file) {
                        $path = $dir . '/' . $file;
                        is_dir($path) ? deleteDir($path) : unlink($path);
                    }
                    return rmdir($dir);
                }
                deleteDir($fullPath);
            } else {
                unlink($fullPath);
            }
            
            echo json_encode(['success' => true]);
            break;
            
        case 'delete_multiple':
            $files = json_decode($_POST['files'] ?? '[]', true);
            
            if (empty($files)) {
                throw new Exception('No files selected');
            }
            
            function deleteDir($dir) {
                $files = array_diff(scandir($dir), ['.', '..']);
                foreach ($files as $file) {
                    $path = $dir . '/' . $file;
                    is_dir($path) ? deleteDir($path) : unlink($path);
                }
                return rmdir($dir);
            }
            
            $deleted = 0;
            $errors = [];
            
            foreach ($files as $file) {
                try {
                    $fullPath = validatePath($file);
                    
                    if (!$fullPath || !file_exists($fullPath)) {
                        $errors[] = "Invalid path: $file";
                        continue;
                    }
                    
                    if (is_dir($fullPath)) {
                        deleteDir($fullPath);
                    } else {
                        unlink($fullPath);
                    }
                    
                    $deleted++;
                } catch (Exception $e) {
                    $errors[] = "Error deleting $file: " . $e->getMessage();
                }
            }
            
            echo json_encode([
                'success' => true,
                'deleted' => $deleted,
                'errors' => $errors
            ]);
            break;
            
        case 'rename':
            $oldPath = $_POST['oldPath'] ?? '';
            $newName = $_POST['newName'] ?? '';
            
            if (empty($newName)) throw new Exception('Name required');
            
            $oldFullPath = validatePath($oldPath);
            if (!$oldFullPath || !file_exists($oldFullPath)) {
                throw new Exception('Invalid path');
            }
            
            $dir = dirname($oldPath);
            $newPath = $dir === '.' ? $newName : $dir . '/' . $newName;
            $newFullPath = validatePath($newPath);
            
            if (!$newFullPath) throw new Exception('Invalid new path');
            if (file_exists($newFullPath)) throw new Exception('Already exists');
            
            rename($oldFullPath, $newFullPath);
            echo json_encode(['success' => true]);
            break;
            
        case 'move':
            $sourcePath = $_POST['sourcePath'] ?? '';
            $targetDir = $_POST['targetDir'] ?? '';
            
            if (empty($sourcePath)) {
                throw new Exception('Source path required');
            }
            
            $sourceFullPath = validatePath($sourcePath);
            $targetDirFullPath = validatePath($targetDir);
            
            if (!$sourceFullPath || !file_exists($sourceFullPath)) {
                throw new Exception('Invalid source path');
            }
            
            if (!$targetDirFullPath || !is_dir($targetDirFullPath)) {
                throw new Exception('Invalid target directory');
            }
            
            // Проверка: нельзя переместить в ту же директорию
            $sourceDir = dirname($sourceFullPath);
            if ($sourceDir === $targetDirFullPath) {
                throw new Exception('Already in this directory');
            }
            
            // Проверка: нельзя переместить папку саму в себя
            if (is_dir($sourceFullPath) && strpos($targetDirFullPath, $sourceFullPath) === 0) {
                throw new Exception('Cannot move folder into itself');
            }
            
            $fileName = basename($sourcePath);
            $newPath = $targetDir ? $targetDir . '/' . $fileName : $fileName;
            $newFullPath = validatePath($newPath);
            
            if (!$newFullPath) throw new Exception('Invalid new path');
            if (file_exists($newFullPath)) throw new Exception('File already exists in target directory');
            
            if (!rename($sourceFullPath, $newFullPath)) {
                throw new Exception('Move operation failed');
            }
            
            echo json_encode(['success' => true]);
            break;
            
        case 'copy':
            $files = json_decode($_POST['files'] ?? '[]', true);
            $targetPath = $_POST['targetPath'] ?? '';
            
            if (empty($files)) {
                throw new Exception('No files selected');
            }
            
            function copyRecursive($source, $dest) {
                if (is_dir($source)) {
                    if (!file_exists($dest)) {
                        mkdir($dest, 0755, true);
                    }
                    
                    $files = array_diff(scandir($source), ['.', '..']);
                    foreach ($files as $file) {
                        copyRecursive($source . '/' . $file, $dest . '/' . $file);
                    }
                } else {
                    copy($source, $dest);
                }
            }
            
            $copied = 0;
            $errors = [];
            
            foreach ($files as $file) {
                try {
                    $sourceFullPath = validatePath($file);
                    
                    if (!$sourceFullPath || !file_exists($sourceFullPath)) {
                        $errors[] = "Invalid path: $file";
                        continue;
                    }
                    
                    $fileName = basename($file);
                    $baseName = pathinfo($fileName, PATHINFO_FILENAME);
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $extension = $extension ? '.' . $extension : '';
                    
                    // Генерируем уникальное имя если файл уже существует
                    $counter = 1;
                    $newFileName = $fileName;
                    $newPath = $targetPath ? $targetPath . '/' . $newFileName : $newFileName;
                    $newFullPath = validatePath($newPath);
                    
                    while ($newFullPath && file_exists($newFullPath)) {
                        $newFileName = $baseName . '_copy' . $counter . $extension;
                        $newPath = $targetPath ? $targetPath . '/' . $newFileName : $newFileName;
                        $newFullPath = validatePath($newPath);
                        $counter++;
                    }
                    
                    if (!$newFullPath) {
                        $errors[] = "Invalid target path for: $file";
                        continue;
                    }
                    
                    copyRecursive($sourceFullPath, $newFullPath);
                    $copied++;
                    
                } catch (Exception $e) {
                    $errors[] = "Error copying $file: " . $e->getMessage();
                }
            }
            
            echo json_encode([
                'success' => true,
                'copied' => $copied,
                'errors' => $errors
            ]);
            break;
            
        case 'upload':
            $path = $_POST['path'] ?? '';
            
            if (!isset($_FILES['file'])) {
                throw new Exception('No file uploaded');
            }
            
            $file = $_FILES['file'];
            
            // Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
                ];
                
                $errorMsg = $errorMessages[$file['error']] ?? 'Unknown upload error';
                throw new Exception($errorMsg);
            }
            
            $targetPath = $path ? $path . '/' . $file['name'] : $file['name'];
            $fullPath = validatePath($targetPath);
            
            if (!$fullPath) throw new Exception('Invalid path');
            
            if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                throw new Exception('Failed to move uploaded file');
            }
            
            // Создать миниатюру для изображений
            $thumbnail = null;
            if (isImage($file['name'])) {
                $thumbPath = createThumbnail($fullPath, $file['name']);
                if ($thumbPath) {
                    $thumbnail = true;
                }
            }
            
            echo json_encode([
                'success' => true, 
                'filename' => $file['name'],
                'thumbnail' => $thumbnail
            ]);
            break;
            
        case 'download':
            $path = $_GET['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !is_file($fullPath)) {
                throw new Exception('Invalid file');
            }
            
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
            
        case 'file_info':
            $path = $_GET['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !file_exists($fullPath)) {
                throw new Exception('Invalid path');
            }
            
            $info = [
                'name' => basename($path),
                'path' => $path,
                'type' => is_dir($fullPath) ? 'folder' : 'file',
                'size' => is_file($fullPath) ? filesize($fullPath) : 0,
                'modified' => filemtime($fullPath),
                'created' => filectime($fullPath),
                'permissions' => substr(sprintf('%o', fileperms($fullPath)), -4),
                'readable' => is_readable($fullPath),
                'writable' => is_writable($fullPath),
                'executable' => is_executable($fullPath)
            ];
            
            // MIME type для файлов
            if (is_file($fullPath) && function_exists('mime_content_type')) {
                $info['mime_type'] = mime_content_type($fullPath);
            }
            
            // Для изображений - получить размеры
            if (is_file($fullPath) && isImage(basename($path))) {
                $imageInfo = @getimagesize($fullPath);
                if ($imageInfo) {
                    $info['image_width'] = $imageInfo[0];
                    $info['image_height'] = $imageInfo[1];
                    $info['image_type'] = image_type_to_mime_type($imageInfo[2]);
                }
                
                // EXIF данные если доступно
                if (function_exists('exif_read_data')) {
                    $exif = @exif_read_data($fullPath);
                    if ($exif) {
                        $info['exif'] = [
                            'camera' => $exif['Model'] ?? null,
                            'date_taken' => $exif['DateTimeOriginal'] ?? null,
                            'exposure' => $exif['ExposureTime'] ?? null,
                            'aperture' => $exif['FNumber'] ?? null,
                            'iso' => $exif['ISOSpeedRatings'] ?? null
                        ];
                    }
                }
            }
            
            // Для папок - подсчитать содержимое
            if (is_dir($fullPath)) {
                $files = array_diff(scandir($fullPath), ['.', '..', '.thumbs']);
                $info['items_count'] = count($files);
            }
            
            echo json_encode(['success' => true, 'info' => $info]);
            break;
            
        case 'folder_stats':
            $path = $_GET['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !is_dir($fullPath)) {
                throw new Exception('Invalid directory');
            }
            
            function calculateDirStats($dir) {
                $totalSize = 0;
                $fileCount = 0;
                $folderCount = 0;
                
                $items = @scandir($dir);
                if ($items === false) {
                    return ['size' => 0, 'files' => 0, 'folders' => 0];
                }
                
                foreach ($items as $item) {
                    if ($item === '.' || $item === '..' || $item === '.thumbs') continue;
                    
                    $itemPath = $dir . '/' . $item;
                    
                    if (is_dir($itemPath)) {
                        $folderCount++;
                        $subStats = calculateDirStats($itemPath);
                        $totalSize += $subStats['size'];
                        $fileCount += $subStats['files'];
                        $folderCount += $subStats['folders'];
                    } else if (is_file($itemPath)) {
                        $fileCount++;
                        $totalSize += @filesize($itemPath) ?: 0;
                    }
                }
                
                return [
                    'size' => $totalSize,
                    'files' => $fileCount,
                    'folders' => $folderCount
                ];
            }
            
            $stats = calculateDirStats($fullPath);
            
            echo json_encode([
                'success' => true,
                'stats' => $stats
            ]);
            break;
            
        case 'thumbnail':
            $path = $_GET['path'] ?? '';
            $fullPath = validatePath($path);
            
            if (!$fullPath || !is_file($fullPath)) {
                throw new Exception('Invalid file');
            }
            
            $thumbPath = createThumbnail($fullPath, basename($path));
            
            if (!$thumbPath || !file_exists($thumbPath)) {
                throw new Exception('Thumbnail not available');
            }
            
            header('Content-Type: image/jpeg');
            header('Cache-Control: public, max-age=31536000');
            header('Content-Length: ' . filesize($thumbPath));
            readfile($thumbPath);
            exit;
            
        case 'download_multiple':
            $files = json_decode($_POST['files'] ?? '[]', true);
            
            if (empty($files)) {
                throw new Exception('No files selected');
            }
            
            // Create ZIP archive
            $zipName = 'files_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = sys_get_temp_dir() . '/' . $zipName;
            
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new Exception('Cannot create ZIP file');
            }
            
            foreach ($files as $file) {
                $fullPath = validatePath($file);
                if ($fullPath && is_file($fullPath)) {
                    $zip->addFile($fullPath, basename($file));
                }
            }
            
            $zip->close();
            
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipName . '"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            unlink($zipPath);
            exit;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
