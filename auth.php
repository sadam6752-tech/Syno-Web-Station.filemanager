<?php
// Система авторизации

require_once 'config.php';

// Настройка сессии
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// Проверка авторизации
function checkAuth() {
    if (!AUTH_ENABLED) {
        return true; // Авторизация отключена
    }
    
    // Проверяем время последней активности
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        logout();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Вход пользователя
function login($username, $password) {
    global $users;
    
    if (!AUTH_ENABLED) {
        return true;
    }
    
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    return false;
}

// Выход пользователя
function logout() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

// Получить имя текущего пользователя
function getCurrentUser() {
    return $_SESSION['username'] ?? 'guest';
}

// Обработка запроса на вход
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    header('Content-Type: application/json');
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        echo json_encode(['success' => true, 'username' => $username]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
    }
    exit;
}

// Обработка запроса на выход
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header('Location: index.php');
    exit;
}

// Проверка авторизации для защищенных страниц
if (!checkAuth() && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php');
    exit;
}
